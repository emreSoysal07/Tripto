<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\PropertyTypeController;
use App\Http\Controllers\Admin\AmenityController;
use App\Http\Controllers\Admin\AuthController;

// Ana Sayfa Yönlendirmesi
Route::get('/', function () {
    return redirect()->route('admin.properties.index');
});

// 1. Sadece Giriş Yapmış Kullanıcıların Erişebileceği Admin Panel Rotaları
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('properties', PropertyController::class);
    Route::resource('property-types', PropertyTypeController::class);
    Route::resource('amenities', AmenityController::class);

    // Çıkış Yap (Logout)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// 2. Giriş Yapmamış Kişilerin Erişeceği Misafir (Guest) Auth Rotaları
Route::prefix('admin')->name('admin.')->middleware('guest')->group(function () {

    // Giriş (Login) Rotaları
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    // Kayıt (Register) Rotaları
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

    // Şifremi Unuttum Rotaları
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
});

// 3. Laravel Mail Şablonunun Bulabilmesi İçin Özel İsimlendirilen Şifre Sıfırlama Rotaları
Route::get('/admin/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/admin/reset-password', [AuthController::class, 'resetPassword'])
    ->middleware('guest')
    ->name('password.update');