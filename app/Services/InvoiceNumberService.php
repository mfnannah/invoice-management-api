<?php

namespace App\Services;

use App\Models\Invoice;

class InvoiceNumberService
{
    public function generate(int $tenantId): string
    {
        $now = now();
        $yearMonth = $now->format('Ym');
        $tenantPart = str_pad($tenantId, 3, '0', STR_PAD_LEFT);

        $lastInvoice = Invoice::where('tenant_id', $tenantId)
            ->whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->orderByDesc('id')
            ->lockForUpdate()
            ->first();

        if ($lastInvoice) {
            preg_match('/-(\d{4})$/', $lastInvoice->invoice_number, $matches);
            $nextSequence = isset($matches[1])
                ? ((int) $matches[1] + 1)
                : 1;
        } else {
            $nextSequence = 1;
        }

        $sequencePart = str_pad($nextSequence, 4, '0', STR_PAD_LEFT);

        return "INV-{$tenantPart}-{$yearMonth}-{$sequencePart}";

    }
}
