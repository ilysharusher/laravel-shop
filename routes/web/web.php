<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ThumbnailController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->get('/', HomeController::class)->name('home');

Route::get('storage/images/{dir}/{method}/{size}/{file}', ThumbnailController::class)
    ->setWheres([
        'method' => 'resize|crop|fit',
        'size' => '\d+x\d+',
        'file' => '.+\.(jpg|jpeg|png|gif)$',
    ])->name('thumbnail');
