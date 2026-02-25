<?php

namespace App\Services\Taxes\Interface;

interface TaxCalculatorInterface
{
    public function calculate(float $amount): float;
}
