<?php

namespace App\Repositories;

use App\Models\Invoice;
use App\Repositories\Contracts\InvoiceRepositoryInterface;

class InvoiceRepository implements InvoiceRepositoryInterface
{
    public function findById(int $id): ?Invoice
    {
        return Invoice::find($id);
    }

    public function create(array $data): Invoice
    {
        return Invoice::create($data);
    }

    public function getByContractId(int $contractId): array
    {
        return Invoice::where('contract_id', $contractId)->get()->toArray();
    }

    public function getByTenantId(int $tenantId): array
    {
        return Invoice::where('tenant_id', $tenantId)->get()->toArray();
    }
}
