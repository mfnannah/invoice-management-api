<?php

namespace App\DTOs;

use App\Http\Requests\StorePaymentRequest;

class RecordPaymentDTO
{
    public function __construct(
        public readonly int $invoice_id,
        public readonly float $amount,
        public readonly string $payment_method,
        public readonly string $payment_date,
        public readonly ?string $reference_number = null,
    ) {}

    public static function fromRequest(StorePaymentRequest $request): self
    {
        return new self(
            invoice_id: $request->validated('invoice_id'),
            amount: (float) $request->validated('amount'),
            payment_method: $request->validated('payment_method'),
            payment_date: $request->validated('payment_date'),
            reference_number: $request->validated('reference_number') ?? null,
        );
    }
}
