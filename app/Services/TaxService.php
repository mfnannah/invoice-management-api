<?php

namespace App\Services;

use App\Services\Taxes\Interface\TaxCalculatorInterface;

class TaxService
{
    /**
     * @var TaxCalculatorInterface[]
     */
    private array $calculators;

    /**
     * @param  TaxCalculatorInterface[]  $calculators
     */
    public function __construct(array $calculators)
    {
        $this->calculators = $calculators;
    }

    public function calculateTotal(float $amount, array $calculators): float
    {
        return array_reduce($calculators, function ($carry, TaxCalculatorInterface $calculator) use ($amount) {
            return $carry + $calculator->calculate($amount);
        }, 0);
    }
}
