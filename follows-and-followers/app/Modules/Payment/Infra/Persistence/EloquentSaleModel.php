<?php

declare(strict_types=1);

namespace App\Modules\Payment\Infra\Persistence;

use App\Models\Sale;
use App\Modules\Payment\Domain\Entity\SaleEntity;

final class EloquentSaleModel
{
    public function create(SaleEntity $sale): SaleEntity
    {
        $eloquentSale = Sale::create($sale->toPersistenceArray());

        return $this->toEntity($eloquentSale);
    }

    public function findByPaymentId(string $paymentId): ?SaleEntity
    {
        $eloquentSale = Sale::query()
            ->where('mp_payment_id', $paymentId)
            ->first();

        if (! $eloquentSale instanceof Sale) {
            return null;
        }

        return $this->toEntity($eloquentSale);
    }

    public function update(SaleEntity $sale): void
    {
        Sale::query()
            ->whereKey($sale->id)
            ->update($sale->toPersistenceArray());
    }

    private function toEntity(Sale $sale): SaleEntity
    {
        return new SaleEntity(
            id: $sale->id,
            userId: $sale->user_id,
            amount: (float) $sale->amount,
            status: (string) $sale->status,
            mpPaymentId: (string) $sale->mp_payment_id,
            mpPaymentData: is_array($sale->mp_payment_data) ? $sale->mp_payment_data : null,
        );
    }
}
