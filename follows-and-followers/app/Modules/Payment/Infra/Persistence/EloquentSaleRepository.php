<?php

declare(strict_types=1);

namespace App\Modules\Payment\Infra\Persistence;

use App\Modules\Payment\Domain\Entity\SaleEntity;
use App\Modules\Payment\Domain\Repo\SaleRepository;

final class EloquentSaleRepository implements SaleRepository
{
    public function __construct(
        private EloquentSaleModel $saleModel,
    ) {}

    public function create(SaleEntity $sale): SaleEntity
    {
        return $this->saleModel->create($sale);
    }

    public function findByPaymentId(string $paymentId): ?SaleEntity
    {
        return $this->saleModel->findByPaymentId($paymentId);
    }

    public function update(SaleEntity $sale): void
    {
        $this->saleModel->update($sale);
    }
}
