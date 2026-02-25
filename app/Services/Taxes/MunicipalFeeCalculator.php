<?php

namespace App\Services\Taxes;

use App\Services\Taxes\Interface\TaxCalculatorInterface;

class MunicipalFeeCalculator implements TaxCalculatorInterface
{
    private float $rate = 0.025;

    public function calculate(float $amount): float
    {
        return $amount * $this->rate;
    }
}
