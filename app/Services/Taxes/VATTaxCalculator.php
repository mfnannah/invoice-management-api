<?php

namespace App\Services\Taxes;

use App\Services\Taxes\Interface\TaxCalculatorInterface;

class VATTaxCalculator implements TaxCalculatorInterface
{
    private float $rate = 0.15;

    public function calculate(float $amount): float
    {
        return $amount * $this->rate;
    }
}
