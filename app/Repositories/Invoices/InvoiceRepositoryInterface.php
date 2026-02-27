<?php

namespace App\Repositories\Invoices;

use App\Models\Invoice;

interface InvoiceRepositoryInterface
{
    public function findById(int $id): ?Invoice;

    public function create(array $data): Invoice;

    public function getByContractId(int $contractId): array;

    public function getByTenantId(int $tenantId): array;
}
