<?php

namespace App\Providers;

use Carbon\CarbonInterval;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpFoundation\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::shouldBeStrict(!app()->isProduction());

        RateLimiter::for('global', static function (Request $request) {
            return Limit::perMinute(500)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('auth', static function (Request $request) {
            return Limit::perMinute(20)->by($request->ip());
        });

        if (app()->isProduction()) {
            DB::listen(static function (QueryExecuted $query) {
                if ($query->time > 100) {
                    logger()
                        ?->channel('telegram')
                        ->debug("Query took longer than 1s: {$query->time} ms - {$query->toRawSql()}");
                }
            });

            app(Kernel::class)->whenRequestLifecycleIsLongerThan(
                CarbonInterval::seconds(3),
                static function ($startedAt, $request, $response) {
                    logger()
                        ?->channel('telegram')
                        ->debug("Request lifecycle took longer than 3s: {$request->fullUrl()}");
                }
            );
        }
    }
}
