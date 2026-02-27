<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subtotal' => ['nullable', 'numeric', 'min:0'],
            'due_date' => ['nullable', 'date'],
        ];
    }
}
