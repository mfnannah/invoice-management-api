<?php

namespace App\Policies;

use App\Enums\InvoiceStatus;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\User;

class InvoicePolicy
{
    public function create(User $user, Contract $contract): bool
    {
        return $user->tenant_id === $contract->tenant_id;
    }

    public function view(User $user, Invoice $invoice): bool
    {
        return $user->tenant_id === $invoice->tenant_id;
    }

    public function recordPayment(User $user, Invoice $invoice): bool
    {
        if ($user->tenant_id !== $invoice->tenant_id) {
            return false;
        }

        if ($invoice->status === InvoiceStatus::CANCELLED) {
            return false;
        }

        return true;
    }

    public function viewAny(User $user, Invoice $invoice): bool
    {
        return true;
    }
}
