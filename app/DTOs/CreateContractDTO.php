<?php

namespace App\DTOs;

use App\Http\Requests\StoreContractRequest;

class CreateContractDTO
{
    public function __construct(
        public readonly string $unit_name,
        public readonly string $customer_name,
        public readonly float $rent_amount,
        public readonly string $start_date,
        public readonly string $end_date,
        public readonly int $tenant_id,

    ) {}

    public static function fromRequest(StoreContractRequest $request): self
    {
        return new self(
            unit_name: $request->validated('unit_name'),
            customer_name: $request->validated('customer_name'),
            rent_amount: $request->validated('rent_amount'),
            start_date: $request->validated('start_date'),
            end_date: $request->validated('end_date'),
            tenant_id: $request->user()->tenant_id,
        );
    }
}
