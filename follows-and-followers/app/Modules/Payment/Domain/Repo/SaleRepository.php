<?php

declare(strict_types=1);

namespace App\Modules\Payment\Domain\Repo;

use App\Modules\Payment\Domain\Entity\SaleEntity;

interface SaleRepository
{
    public function create(SaleEntity $sale): SaleEntity;

    public function findByPaymentId(string $paymentId): ?SaleEntity;

    /**
     * @param  array<string, mixed>|null  $data
     */
    public function update(SaleEntity $sale): void;
}
