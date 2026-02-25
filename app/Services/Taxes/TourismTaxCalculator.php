<?php

namespace App\Services\Taxes;

use App\Services\Taxes\Interface\TaxCalculatorInterface;

class TourismTaxCalculator implements TaxCalculatorInterface
{
    private float $rate = 0.05;

    public function calculate(float $amount): float
    {
        return $amount * $this->rate;
    }
}
