<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/home';

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->routes(function () {
            // Routes Web
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
            // Routes API
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));
        });
    }
}
