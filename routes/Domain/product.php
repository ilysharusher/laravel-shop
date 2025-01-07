<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('product/{product:slug}', ProductController::class)->name('product.show');
