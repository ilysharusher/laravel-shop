<?php

use App\Http\Controllers\CatalogController;
use Illuminate\Support\Facades\Route;

Route::middleware('catalog.view')
    ->get('/catalog/{category:slug?}', CatalogController::class)
    ->name('catalog');
