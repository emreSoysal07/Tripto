<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\PropertyTypeController;
use App\Http\Controllers\Admin\AmenityController;
use App\Http\Controllers\Admin\AuthController;

Route::get('/', function () {
    return redirect()->route('admin.properties.index');
});

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('properties', PropertyController::class);
    Route::resource('property-types', PropertyTypeController::class);
    Route::resource('amenities', AmenityController::class);
    
    // Giriş (Login) Rotaları -> Otomatik admin.login ve admin.login.submit olacak
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    // Kayıt (Register) Rotaları -> Otomatik admin.register ve admin.register.submit olacak
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

    // Şifremi Unuttum Rotaları -> Otomatik admin.password.request ve admin.password.email olacak
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');

});