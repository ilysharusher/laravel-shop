<?php

namespace App\Actions;

use App\Contracts\RouteRegistrar;
use RuntimeException;

class mapRoutesAction
{
    public static function mapRoutes($router, array $registrars): void
    {
        foreach ($registrars as $registrar) {
            if (!class_exists($registrar) || !is_subclass_of($registrar, RouteRegistrar::class)) {
                throw new RuntimeException(
                    sprintf(
                        'Cannot map routers \'%s\', it is not a valid routes class',
                        $registrar
                    )
                );
            }
            (new $registrar())->map($router);
        }
    }
}
