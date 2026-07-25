<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        // Giriş mantığı buraya gelecek
        return redirect()->back();
    }

    public function register(Request $request)
    {
        // Kayıt veritabanı kayıt mantığı buraya gelecek
        return redirect()->back();
    }

    public function sendResetLinkEmail(Request $request)
    {
        // Şifre sıfırlama e-postası gönderme mantığı buraya gelecek
        return redirect()->back();
    }
}
