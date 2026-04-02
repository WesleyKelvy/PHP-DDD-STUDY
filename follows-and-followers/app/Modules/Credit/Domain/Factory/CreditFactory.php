<?php

declare(strict_types=1);

namespace App\Modules\Credit\Domain\Factory;

use App\Modules\Credit\Domain\Entity\CreditEntity;
use App\Modules\Credit\Domain\ValueObject\CreditRequestValueObject;

final class CreditFactory
{
    public static function createCredit(
        CreditRequestValueObject $credit,
    ): CreditEntity {
        return new CreditEntity(
            id: null,
            userId: $credit->userId,
            saleId: $credit->saleId,
            total: $credit->total,
            used: $credit->used,
            reserved: $credit->reserved,
            expiresAt: $credit->expiresAt,
        );
    }
}
