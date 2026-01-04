<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/home';

    public function boot(): void
    {
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }

        $this->configureRateLimiting();

        Route::prefix('api')
            ->middleware(['api', 'verify-app-token'])
            ->as('api:')
            ->group(function () {
                /**
                 * v1
                 */
                Route::prefix('v1')->as('v1:')->group(base_path('routes/api/v1.php'));
            });

        Route::prefix('exchange')->as('exchange:')->group(function () {
            Route::prefix('v1')->as('v1:')->group(base_path('routes/api/exchange.php'));
        });
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
