<?php

declare(strict_types=1);

namespace App\Modules\Credit\Infra\Persistence;

use App\Modules\Credit\Domain\Entity\CreditEntity;
use App\Modules\Credit\Domain\Repo\CreditRepository;
use CreditEntityCollection;

final class EloquentCreditRepository implements CreditRepository
{
    public function __construct(
        private EloquentCreditModel $creditModel,
    ) {}

    public function create(CreditEntity $credit): void
    {
        $this->creditModel->create($credit);
    }

    public function getAllByUserId(string $userId): CreditEntityCollection
    {
        return $this->creditModel->getAllByUserId($userId);
    }

    public function findBySaleId(string $saleId): ?CreditEntity
    {
        return $this->creditModel->findBySaleId($saleId);
    }

    public function update(CreditEntity $sale): void
    {
        $this->creditModel->update($sale);
    }
}
