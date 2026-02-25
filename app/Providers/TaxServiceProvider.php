<?php

namespace App\Providers;

use App\Services\Taxes\MunicipalFeeCalculator;
use App\Services\Taxes\TourismTaxCalculator;
use App\Services\Taxes\VATTaxCalculator;
use App\Services\TaxService;
use Illuminate\Support\ServiceProvider;

class TaxServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(TaxService::class, function ($app) {
            $calculators = [
                $app->make(VATTaxCalculator::class),
                $app->make(MunicipalFeeCalculator::class),
                $app->make(TourismTaxCalculator::class),
            ];

            return new TaxService($calculators);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
