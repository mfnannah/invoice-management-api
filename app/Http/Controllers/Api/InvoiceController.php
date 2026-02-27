<?php

namespace App\Http\Controllers\Api;

use App\DTOs\CreateInvoiceDTO as DTOsCreateInvoiceDTO;
use App\DTOs\RecordPaymentDTO as DTOsRecordPaymentDTO;
use App\Enums\InvoiceStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Resources\ContractSummaryResource;
use App\Http\Resources\InvoiceResource;
use App\Models\Contract;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private InvoiceService $invoiceService) {}

    // POST /contracts/{contract}/invoices
    public function store(StoreInvoiceRequest $request, Contract $contract)
    {
        $this->authorize('create', [Invoice::class, $contract]);

        $dto = DTOsCreateInvoiceDTO::fromRequest($request, $contract);

        $invoice = $this->invoiceService->createInvoice($dto);

        return InvoiceResource::make($invoice)
            ->response()
            ->setStatusCode(201);
    }

    // GET /contracts/{contract}/invoices
    public function index(Contract $contract, Request $request)
    {
        $invoicesQuery = $contract->invoices()
            ->with('payments');
        if ($request->filled('status') && in_array($request->input('status'), array_column(InvoiceStatus::cases(), 'value'))) {
            $invoicesQuery->where('status', $request->input('status'));
        }
        if ($request->filled('from') && $request->filled('to')) {
            $from = Carbon::parse($request->input('from'))->startOfDay();
            $to = Carbon::parse($request->input('to'))->endOfDay();
            $invoicesQuery->whereBetween('created_at', [$from, $to]);
        }
        $invoices = $invoicesQuery->latest()
            ->paginate();

        return InvoiceResource::collection($invoices);
    }

    // GET /invoices/{invoice}
    public function show(Invoice $invoice)
    {
        $this->authorize('view', $invoice);

        $invoice->load(['contract', 'payments']);

        return InvoiceResource::make($invoice);
    }

    // POST /invoices/{invoice}/payments
    public function recordPayment(StorePaymentRequest $request, Invoice $invoice)
    {
        $this->authorize('recordPayment', $invoice);

        $dto = DTOsRecordPaymentDTO::fromRequest($request, $invoice);

        $payment = $this->invoiceService->recordPayment($dto);

        return response()->json([
            'message' => 'Payment recorded successfully',
        ], 201);
    }

    // GET /contracts/{contract}/summary
    public function summary(Contract $contract)
    {
        $summary = $this->invoiceService
            ->getContractSummary($contract->id);

        return ContractSummaryResource::make($summary);
    }
}
