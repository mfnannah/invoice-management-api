<?php

namespace App\DTOs;

use App\Http\Requests\StorePaymentRequest;

class RecordPaymentDTO
{
    public function __construct(
        public readonly int $invoice_id,
        public readonly float $amount,
        public readonly string $payment_method,
        public readonly string $paid_at,
        public readonly ?string $reference_number = null,
    ) {}

    public static function fromRequest(StorePaymentRequest $request, $invoice): self
    {
        return new self(
            invoice_id: $invoice->id,
            amount: (float) $request->validated('amount'),
            payment_method: $request->validated('payment_method'),
            paid_at: $request->validated('paid_at'),
            reference_number: $request->validated('reference_number') ?? null,
        );
    }
}
