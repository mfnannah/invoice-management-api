<?php

namespace App\Policies;

use App\Models\Contract;
use App\Models\User;

class ContractPolicy
{
    public function create(User $user): bool
    {
        return true;
    }

    public function view(User $user, Contract $contract): bool
    {
        return $user->tenant_id === $contract->tenant_id;
    }

    public function update(User $user, Contract $contract): bool
    {
        return $user->tenant_id === $contract->tenant_id;
    }

    public function delete(User $user, Contract $contract): bool
    {
        return $user->tenant_id === $contract->tenant_id;
    }
}
