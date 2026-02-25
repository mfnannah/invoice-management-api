<?php

namespace App\Repositories;

use App\Models\Contract;
use App\Repositories\Contracts\ContractRepositoryInterface;

class ContractRepository implements ContractRepositoryInterface
{
    public function findById(int $id): ?Contract
    {
        return Contract::find($id);
    }

    public function create(array $data): Contract
    {
        return Contract::create($data);
    }

    public function getByTenantId(int $tenantId): array
    {
        return Contract::where('tenant_id', $tenantId)->get()->toArray();
    }
}
