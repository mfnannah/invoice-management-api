<?php

namespace App\Services;

use App\DTOs\CreateInvoiceDTO as DTOsCreateInvoiceDTO;
use App\DTOs\RecordPaymentDTO as DTOsRecordPaymentDTO;
use App\Enums\ContractStatus;
use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\Payment;
use App\Repositories\Contracts\ContractRepositoryInterface;
use App\Repositories\Invoices\InvoiceRepositoryInterface;
use App\Repositories\Payments\PaymentRepositoryInterface;
use App\Services\Taxes\VATTaxCalculator;
use Carbon\Carbon;
use DomainException;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    public function __construct(
        private ContractRepositoryInterface $contractepository,
        private InvoiceRepositoryInterface $invoiceRepository,
        private PaymentRepositoryInterface $paymentRepository,
        private TaxService $taxService,
        private InvoiceNumberService $invoiceNumberService,
    ) {}

    /**
     * Create invoice from contract
     */
    public function createInvoice(DTOsCreateInvoiceDTO $dto): Invoice
    {
        return DB::transaction(function () use ($dto) {

            $contract = $this->contractepository->findById($dto->contract_id);

            if (! $contract || $contract->status !== ContractStatus::ACTIVE) {
                throw new DomainException('Cannot create invoice for non-active contract.');
            }

            $taxAmount = $this->taxService->calculateTotal($dto->subtotal, [new VATTaxCalculator]);

            $total = $dto->subtotal + $taxAmount;

            $invoiceNumber = $this->invoiceNumberService->generate($contract->tenant_id);

            return $this->invoiceRepository->create([
                'tenant_id' => $contract->tenant_id,
                'contract_id' => $contract->id,
                'invoice_number' => $invoiceNumber,
                'subtotal' => $dto->subtotal,
                'tax_amount' => $taxAmount,
                'total' => $total,
                'status' => InvoiceStatus::PENDING,
                'due_date' => Carbon::parse($dto->due_date)->format('Y-m-d'),
                'paid_at' => now()->format('Y-m-d'),
            ]);
        });
    }

    /**
     * Record payment
     */
    public function recordPayment(DTOsRecordPaymentDTO $dto): Payment
    {
        return DB::transaction(function () use ($dto) {

            $invoice = $this->invoiceRepository->findById($dto->invoice_id);

            if (! $invoice) {
                throw new DomainException('Invoice not found.');
            }

            $totalPaid = $this->paymentRepository->getByInvoiceId($invoice->id)->sum('amount');

            $remaining = $invoice->total - $totalPaid;

            if ($dto->amount > $remaining) {
                throw new DomainException('Payment exceeds remaining balance.');
            }

            $payment = $this->paymentRepository->create([
                'tenant_id' => $invoice->tenant_id,
                'invoice_id' => $invoice->id,
                'amount' => $dto->amount,
                'paid_at' => Carbon::parse($dto->paid_at)->format('Y-m-d'),
                'payment_method' => $dto->payment_method,
            ]);

            $newTotalPaid = $totalPaid + $dto->amount;

            if ($newTotalPaid == $invoice->total) {
                $invoice->status = InvoiceStatus::PAID;
            } elseif ($newTotalPaid > 0) {
                $invoice->status = InvoiceStatus::PARTIALLY_PAID;
            }

            $invoice->save();

            return $payment;
        });
    }

    /**
     * Contract Financial Summary
     */
    public function getContractSummary(int $contractId): array
    {
        $invoices = $this->invoiceRepository->getByContractId($contractId);

        $totalInvoiced = collect($invoices)->sum('total');

        $totalPaid = collect($invoices)->sum(function ($invoice) {
            return collect(
                $this->paymentRepository->getByInvoiceId($invoice['id'])
            )->sum('amount');
        });

        return [
            'contract_id' => $contractId,
            'total_invoiced' => $totalInvoiced,
            'total_paid' => $totalPaid,
            'outstanding' => $totalInvoiced - $totalPaid,
            'invoices_count' => collect($invoices)->count(),
            'latest_invoice_date' => Carbon::parse(collect($invoices)->max('created_at'))->format('Y-m-d'),
        ];
    }
}
