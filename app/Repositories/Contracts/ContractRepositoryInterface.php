<?php

namespace App\Repositories\Contracts;

use App\Models\Contract;
use Illuminate\Database\Eloquent\Collection;

interface ContractRepositoryInterface
{
    public function findById(int $id): ?Contract;

    public function create(array $data): Contract;

    public function getByTenantId(int $tenantId): Collection;
}
