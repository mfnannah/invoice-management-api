<?php

namespace App\Providers;

use App\Services\Taxes\Interface\TaxCalculatorInterface;
use App\Services\Taxes\MunicipalFeeCalculator;
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
            ];

            return new TaxService($calculators);
        });
        $this->app->bind(TaxCalculatorInterface::class, VATTaxCalculator::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
