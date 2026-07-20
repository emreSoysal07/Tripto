<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PropertyController;

Route::get('/', function () {
    return redirect()->route('admin.properties.index');
});

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('properties', PropertyController::class);

});