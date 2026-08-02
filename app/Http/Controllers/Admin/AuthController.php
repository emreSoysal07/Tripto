<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendResetCodeMail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

    // Kod doğrulama formunu gösterir
    public function showVerifyCodeForm(Request $request)
    {
        return view('admin.auth.reset-password-code', ['email' => $request->email]);
    }

    // --- FORM İŞLEME (POST) METOTLARI ---

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'E-posta adresi zorunludur.',
            'email.email'       => 'Geçerli bir e-posta adresi giriniz.',
            'password.required' => 'Şifre alanı boş bırakılamaz.',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

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
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin',
        ]);
    
        Auth::login($user);        
        
        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    // 1. E-posta ve 6 Haneli Kod Gönderme
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'E-posta adresi zorunludur.',
            'email.email'    => 'Geçerli bir e-posta adresi giriniz.',
            'email.exists'   => 'Bu e-posta adresine ait bir kullanıcı bulunamadı.',
        ]);

        $code = rand(100000, 999999);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token'      => $code,
                'code'       => $code,
                'created_at' => Carbon::now()
            ]
        );

        Mail::to($request->email)->send(new SendResetCodeMail($code));

        // Rota adı web.php ile eşleştirildi: admin.password.verify
        return redirect()->route('admin.password.verify', ['email' => $request->email])
                         ->with('status', '6 haneli doğrulama kodu e-posta adresinize gönderildi!');
    }

    // 2. Kodu Doğrulama ve Şifreyi Güncelleme (POST)
    public function verifyCode(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|exists:users,email',
            'code'     => 'required|numeric|digits:6',
            'password' => 'required|string|min:8|confirmed', // Yeni eklendi
        ], [
            'email.required'     => 'E-posta adresi zorunludur.',
            'email.exists'       => 'Bu e-posta adresine ait kayıt bulunamadı.',
            'code.required'      => 'Doğrulama kodu zorunludur.',
            'code.digits'        => 'Doğrulama kodu 6 haneli olmalıdır.',
            'password.required'  => 'Yeni şifre alanı zorunludur.',
            'password.min'       => 'Yeni şifre en az 8 karakter olmalıdır.',
            'password.confirmed' => 'Şifre tekrarları birbiriyle eşleşmiyor.',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('code', $request->code)
            ->first();

        if (!$record) {
            return back()->withErrors(['code' => 'Girdiğiniz 6 haneli kod geçersiz veya hatalı.'])->withInput();
        }

        // 15 dakikalık zaman aşımı kontrolü
        if (Carbon::parse($record->created_at)->addMinutes(15)->isPast()) {
            return back()->withErrors(['code' => 'Bu kodun kullanım süresi dolmuş. Lütfen tekrar kod isteyin.'])->withInput();
        }

        // Şifreyi Güncelle
        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password)
        ]);

        // Kullanılan kodu temizle
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('admin.login')->with('status', 'Şifreniz başarıyla sıfırlandı! Yeni şifrenizle giriş yapabilirsiniz.');
    }

    // Standart Şifre Yenileme Formu (Mail İçi Link Kullanılırsa)
    public function showResetPasswordForm($token)
    {
        return view('admin.auth.reset-password', ['token' => $token]);
    }

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