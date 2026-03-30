<?php

declare(strict_types=1);

namespace App\Modules\Credit\Infra\Persistence;

use App\Models\Credit;
use App\Modules\Credit\Domain\Entity\CreditEntity;
use CreditEntityCollection;

final class EloquentCreditModel
{
    public function create(CreditEntity $credit): CreditEntity
    {
        $eloquentCredit = Credit::create($credit->toPersistenceArray());

        return $this->toEntity($eloquentCredit);
    }

    public function getAllByUserId(string $userId): CreditEntityCollection
    {
        /** @var CreditEntity[] $entities */
        $entities = Credit::query()
            ->where('userId', $userId)
            ->get()
            ->map(fn ($credit) => $this->toEntity($credit))
            ->all();

        return CreditEntityCollection::fromArray($entities);
    }

    public function findBySaleId(string $saleId): ?CreditEntity
    {
        $eloquentCredit = credit::query()
            ->where('sale_id', $saleId)
            ->first();

        if (! $eloquentCredit instanceof Credit) {
            return null;
        }

        return $this->toEntity($eloquentCredit);
    }

    public function update(CreditEntity $credit): void
    {
        credit::query()
            ->whereKey($credit->id)
            ->update($credit->toPersistenceArray());
    }

    private function toEntity(credit $credit): CreditEntity
    {
        return new CreditEntity(
            id: $credit->id,
            userId: $credit->user_id,
            saleId: $credit->sale_id,
            total: $credit->total,
            used: $credit->used,
            reserved: $credit->reserved,
            expiresAt: $credit->expires_at,
        );
    }
}
