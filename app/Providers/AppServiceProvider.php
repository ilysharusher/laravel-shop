<?php

namespace App\Providers;

use Carbon\CarbonInterval;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Database\Eloquent\Model;
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

        DB::whenQueryingForLongerThan(500, static function ($query, $time) {
            logger()?->channel('telegram')->info('Slow query', [
                'query' => $query,
                'time' => $time,
            ]);
        });

        $kernel = app(Kernel::class);

        $kernel->whenRequestLifecycleIsLongerThan(
            CarbonInterval::seconds(3),
            static function ($request, $time) {
                logger()?->channel('telegram')->info('Slow request', [
                    'request' => $request,
                    'time' => $time,
                ]);
            }
        );
    }
}
