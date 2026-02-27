<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractSummaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'contract_id' => $this['contract_id'] ?? null,
            'total_invoiced' => $this['total_invoiced'],
            'total_paid' => $this['total_paid'],
            'outstanding_balance' => $this['outstanding'],
            'invoices_count' => $this['invoices_count'] ?? null,
            'latest_invoice_date' => $this['latest_invoice_date'] ?? null,
        ];
    }
}
