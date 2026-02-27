<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'unit_name' => $this->unit_name,
            'customer_name' => $this->customer_name,
            'rent_amount' => $this->rent_amount,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,

            'invoices' => InvoiceResource::collection($this->whenLoaded('invoices')),
        ];
    }
}
