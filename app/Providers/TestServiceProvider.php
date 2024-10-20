<?php

namespace App\Providers;

use App\Faker\FakerThumbnailProvider;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Support\ServiceProvider;

class TestServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(Generator::class, function()
        {
            $faker = Factory::create();
            $faker->addProvider(new FakerThumbnailProvider($faker));

            return $faker;
        });

        $this->app->bind(
            Generator::class . ':' . config('app.faker_locale'),
            Generator::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
