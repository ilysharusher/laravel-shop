<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->get('/', HomeController::class)->name('home');

Route::controller(AuthController::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', 'login')->name('login');
        Route::post('login', 'login_store')->name('login.store');

        Route::get('register', 'register')->name('register');
        Route::post('register', 'register_store')->name('register.store');

        Route::get('forgot-password', 'forgotPassword')->name('forgot.password');
    });

    Route::delete('logout', 'logout')->name('logout');
});
