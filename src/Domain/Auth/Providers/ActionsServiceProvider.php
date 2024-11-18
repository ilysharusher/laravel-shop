<?php

namespace Domain\Auth\Providers;

use Domain\Auth\Actions\RegisterUserAction;
use Domain\Auth\Actions\SocialAuthAction;
use Domain\Auth\Contracts\RegisterUserContract;
use Domain\Auth\Contracts\SocialAuthContract;
use Illuminate\Support\ServiceProvider;

class ActionsServiceProvider extends ServiceProvider
{
    public array $bindings = [
        RegisterUserContract::class => RegisterUserAction::class,
        SocialAuthContract::class => SocialAuthAction::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
