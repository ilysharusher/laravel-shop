<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SocialAuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::middleware('throttle:auth')->group(function () {
        Route::get('register', [RegisterController::class, 'page'])->name('register');
        Route::post('register', [RegisterController::class, 'handle'])->name('register.store');

        Route::get('login', [LoginController::class, 'page'])->name('login');
        Route::post('login', [LoginController::class, 'handle'])->name('login.store');

        Route::name('password.')->group(function () {
            Route::get('forgot-password', [ForgotPasswordController::class, 'page'])->name('request');
            Route::post('forgot-password', [ForgotPasswordController::class, 'handle'])->name('email');

            Route::get('reset-password/{token}', [ResetPasswordController::class, 'page'])->name('reset');
            Route::post('reset-password', [ResetPasswordController::class, 'handle'])->name('update');
        });

        Route::get('auth/redirect/{driver}', [SocialAuthController::class, 'redirect'])->name('socialite.redirect');
        Route::get('auth/callback/{driver}', [SocialAuthController::class, 'callback'])->name('socialite.callback');
    });
});

Route::middleware('auth')->delete('logout', [LogoutController::class, 'handle'])->name('logout');
