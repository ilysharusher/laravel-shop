<?php

use App\Actions\mapRoutesAction;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Sentry\Laravel\Integration;

$registrars = [
    \App\Routing\AppRegistrar::class,
    \Domain\Auth\Routing\AuthRegistrar::class,
];

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: fn (Registrar $router) => mapRoutesAction::mapRoutes($router, $registrars),
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web('throttle:global');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->dontReportDuplicates();

        $exceptions->renderable(function (DomainException $exception) {
            flash()->alert($exception->getMessage());

            return back();
        });

        Integration::handles($exceptions);
    })->create();
