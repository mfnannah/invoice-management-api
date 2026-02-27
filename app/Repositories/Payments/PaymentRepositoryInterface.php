<?php

namespace App\Repositories\Payments;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Collection;

interface PaymentRepositoryInterface
{
    public function findById(int $id): ?Payment;

    public function create(array $data): Payment;

    public function getByInvoiceId(int $invoiceId): Collection;

    public function getByTenantId(int $tenantId): array;
}
