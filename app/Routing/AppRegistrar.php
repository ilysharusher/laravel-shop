<?php

namespace App\Routing;

use App\Contracts\RouteRegistrar;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ThumbnailController;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Support\Facades\Route;

class AppRegistrar implements RouteRegistrar
{
    public function map(Registrar $registrar): void
    {
        Route::middleware('web')->group(function () {
            Route::middleware('auth')->get('/', HomeController::class)->name('home');

            Route::get('storage/images/{dir}/{method}/{size}/{file}', ThumbnailController::class)
                ->setWheres([
                    'method' => 'resize|crop|fit',
                    'size' => '\d+x\d+',
                    'file' => '.+\.(jpg|jpeg|png|gif)$',
                ])->name('thumbnail');
        });
    }
}
