<?php

namespace App\Services;

use App\DTOs\CreateContractDTO;
use App\Enums\ContractStatus;
use App\Models\Contract;
use App\Repositories\Contracts\ContractRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class ContractService
{
    public function __construct(
        private ContractRepositoryInterface $contractRepository,
    ) {}

    public function createContract(CreateContractDTO $dto): Contract
    {
        return DB::transaction(function () use ($dto) {
            $data = [
                'tenant_id' => $dto->tenant_id,
                'unit_name' => $dto->unit_name,
                'customer_name' => $dto->customer_name,
                'rent_amount' => $dto->rent_amount,
                'start_date' => Carbon::parse($dto->start_date)->format('Y-m-d'),
                'end_date' => Carbon::parse($dto->end_date)->format('Y-m-d'),
                'status' => ContractStatus::ACTIVE,
            ];

            return $this->contractRepository->create($data);
        });
    }

    public function updateContract(Contract $contract, CreateContractDTO $dto): Contract
    {
        if ($contract->tenant_id !== $dto->tenant_id) {
            throw new \Exception('Unauthorized tenant.');
        }

        return DB::transaction(function () use ($contract, $dto) {
            $data = [
                'unit_name' => $dto->unit_name,
                'customer_name' => $dto->customer_name,
                'rent_amount' => $dto->rent_amount,
                'start_date' => $dto->start_date,
                'end_date' => $dto->end_date,
            ];

            return $this->contractRepository->update($contract, $data);
        });
    }

    public function deleteContract(Contract $contract): void
    {
        DB::transaction(function () use ($contract) {
            $this->contractRepository->delete($contract);
        });
    }

    public function getAllByTenantID(int $tenantId)
    {
        return $this->contractRepository->getByTenantId($tenantId);
    }

    public function getByIdForTenant(int $contractId, int $tenantId): Contract
    {
        $contract = $this->contractRepository->findById($contractId);

        if (! $contract || $contract->tenant_id !== $tenantId) {
            throw new ModelNotFoundException('Contract not found for your tenant.');
        }

        return $contract;
    }
}
