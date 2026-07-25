<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    // --- GÖRÜNÜM (GET) METOTLARI ---

    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function showRegisterForm()
    {
        return view('admin.auth.register');
    }

    public function showForgotPasswordForm()
    {
        return view('admin.auth.forgot-password');
    }

    // --- FORM İŞLEME (POST) METOTLARI ---

    public function login(Request $request)
    {
        // 1. Form Doğrulama
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'E-posta adresi zorunludur.',
            'email.email'       => 'Geçerli bir e-posta adresi giriniz.',
            'password.required' => 'Şifre alanı boş bırakılamaz.',
        ]);

        // Beni Hatırla Seçeneği
        $remember = $request->has('remember');

        // 2. Giriş Kontrolü (Auth::attempt)
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate(); // Oturum güvenliği için session ID yenilenir

            return redirect()->intended(route('admin.dashboard'));
        }

        // 3. Hatalı Giriş Durumu
        return back()->withErrors([
            'email' => 'Girdiğiniz e-posta veya şifre hatalı.',
        ])->onlyInput('email');

    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'terms'    => 'accepted',
        ]);
    
        $user = User::create([
            'username'     => $request->username, // Formdaki 'username' veritabanındaki 'name' sütununa yazılır
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin', // Admin kayıt paneli olduğu için varsayılan olarak admin atayabilirsin
        ]);
    
        Auth::login($user);        
        
        return redirect()->back();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function sendResetLinkEmail(Request $request)
    {
        // 1. E-posta doğrulaması
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'E-posta adresi zorunludur.',
            'email.email'    => 'Geçerli bir e-posta adresi giriniz.',
            'email.exists'   => 'Bu e-posta adresine ait bir kullanıcı bulunamadı.',
        ]);

        // 2. Sıfırlama bağlantısını oluştur ve gönder
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // 3. Duruma göre geri bildirim dön
        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Şifre sıfırlama bağlantısı e-posta adresinize gönderildi!');
        }

        return back()->withErrors(['email' => 'Sıfırlama bağlantısı gönderilemedi.']);
    }

    // Şifre Yenileme Formunu Göster (Maildeki Link Tıklandığında)
    public function showResetPasswordForm($token)
    {
        return view('admin.auth.reset-password', ['token' => $token]);
    }

    // Yeni Şifreyi Kaydet
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
        ], [
            'password.confirmed' => 'Şifre tekrarları birbiriyle eşleşmiyor.',
            'password.min'       => 'Şifre en az 6 karakter olmalıdır.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('admin.login')->with('status', 'Şifreniz başarıyla sıfırlandı! Yeni şifrenizle giriş yapabilirsiniz.');
        }

        return back()->withErrors(['email' => 'Şifre sıfırlama işlemi başarısız oldu.']);
    }
}
