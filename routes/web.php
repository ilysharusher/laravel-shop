<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->get('/', HomeController::class)->name('home');

Route::controller(AuthController::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', 'login')->name('login');
        Route::get('register', 'register')->name('register');
        Route::get('forgot-password', 'forgotPassword')->name('forgot-password');
    });
});
