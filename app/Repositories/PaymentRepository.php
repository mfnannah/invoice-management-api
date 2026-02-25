<?php

namespace App\Repositories;

use App\Models\Payment;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function findById(int $id): ?Payment
    {
        return Payment::find($id);
    }

    public function create(array $data): Payment
    {
        return Payment::create($data);
    }

    public function getByInvoiceId(int $invoiceId): Collection
    {
        return Payment::where('invoice_id', $invoiceId)->get();
    }

    public function getByTenantId(int $tenantId): array
    {
        return Payment::where('tenant_id', $tenantId)->get()->toArray();
    }
}
