<?php

namespace App\Services\Invoices;

use App\Models\Invoice;

class InvoiceNumberService
{
    public function generate(int $tenantId): string
    {
        $year = now()->format('Y');
        $lastInvoice = Invoice::where('tenant_id', $tenantId)
            ->whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastInvoice) {
            preg_match('/-(\d+)$/', $lastInvoice->invoice_number, $matches);
            $nextIncrement = isset($matches[1]) ? ((int) $matches[1] + 1) : 1;
        } else {
            $nextIncrement = 1;
        }

        return sprintf('INV-%s%s-%04d', $year, $tenantId, $nextIncrement);
    }
}
