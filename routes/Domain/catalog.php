<?php

use App\Http\Controllers\CatalogController;
use Illuminate\Support\Facades\Route;

Route::get('/catalog/{category:slug?}', CatalogController::class)->name('catalog');
