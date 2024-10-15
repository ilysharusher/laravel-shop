<?php

namespace App\Providers;

use Carbon\CarbonInterval;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Database\Connection;
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
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::shouldBeStrict(!app()->isProduction());

        RateLimiter::for('global', static function (Request $request) {
            return Limit::perMinute(500)
                ->by($request->user()?->id ?: $request->ip())
                ->response(static function () {
                    return response('Too Many Requests', Response::HTTP_TOO_MANY_REQUESTS);
                });
        });

        if (app()->isProduction()) {
            DB::whenQueryingForLongerThan(CarbonInterval::seconds(5), static function (Connection $connection, QueryExecuted $event) {
                logger()
                    ?->channel('telegram')
                    ->debug("Queries lifecycle took longer than 500ms: {$connection->totalQueryDuration()} ms - {$event->sql}");
            });

            DB::listen(static function (QueryExecuted $query) {
                if ($query->time > 100) {
                    logger()
                        ?->channel('telegram')
                        ->debug("Query took longer than 100ms: {$query->time} ms - {$query->toRawSql()}");
                }
            });

            $kernel = app(Kernel::class);

            $kernel->whenRequestLifecycleIsLongerThan(
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
