<?php

namespace App\Http\Controllers\Api;

use App\DTOs\CreateContractDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContractRequest;
use App\Http\Resources\ContractResource;
use App\Models\Contract;
use App\Services\ContractService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ContractController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private ContractService $contractService) {}

    public function index()
    {
        $contracts = $this->contractService->getAllByTenantID(auth()->user()->tenant_id);

        return ContractResource::collection($contracts);
    }

    public function store(StoreContractRequest $request)
    {
        $this->authorize('create', [Contract::class]);

        $dto = CreateContractDTO::fromRequest($request);

        $contract = $this->contractService->createContract($dto);

        return ContractResource::make($contract)
            ->response()
            ->setStatusCode(201);
    }

    public function show(Contract $contract)
    {
        $this->authorize('view', $contract);

        return ContractResource::make($contract);
    }

    public function update(StoreContractRequest $request, Contract $contract)
    {
        $this->authorize('update', $contract);

        $dto = CreateContractDTO::fromRequest($request);

        $updated = $this->contractService->updateContract($contract, $dto);

        return ContractResource::make($updated);
    }

    public function destroy(Contract $contract)
    {
        $this->authorize('delete', $contract);

        $this->contractService->deleteContract($contract);

        return response()->json(null, 204);
    }
}
