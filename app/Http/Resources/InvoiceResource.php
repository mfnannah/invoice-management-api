<?php

namespace App\Http\Resources;

use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $totalPaid = $this->payments->sum('amount');
        $contract = Contract::find($this->contract_id);

        return [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,
            'subtotal' => $this->subtotal,
            'tax_amount' => $this->tax_amount,
            'total' => $this->total,
            'status' => $this->status,
            'due_date' => $this->due_date,
            'paid_at' => $this->paid_at,

            'remaining_balance' => $contract?->rent_amount - $this->subtotal,

            'contract' => new ContractSummaryResource($this->whenLoaded('contract')),
            'payments' => PaymentResource::collection($this->whenLoaded('payments')),
        ];
    }
}
