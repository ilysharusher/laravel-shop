<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->get('/', HomeController::class)->name('home');

Route::controller(AuthController::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::middleware('throttle:auth')->group(function () {
            Route::get('login', 'login')->name('login');
            Route::post('login', 'login_store')->name('login.store');

            Route::get('auth/redirect', 'redirect')->name('redirect');
            Route::get('auth/callback', 'callback')->name('callback');

            Route::get('register', 'register')->name('register');
            Route::post('register', 'register_store')->name('register.store');
        });

        Route::name('password.')->group(function () {
            Route::get('forgot-password', 'forgot_password')->name('request');
            Route::post('forgot-password', 'forgot_password_store')->name('email');

            Route::get('reset-password/{token}', 'reset_password')->name('reset');
            Route::post('reset-password', 'reset_password_store')->name('update');
        });
    });

    Route::delete('logout', 'logout')->name('logout');
});
