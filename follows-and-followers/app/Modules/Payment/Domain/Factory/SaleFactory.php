<?php

declare(strict_types=1);

namespace App\Modules\Payment\Domain\Factory;

use App\Modules\Payment\Domain\Entity\SaleEntity;

final class SaleFactory
{
    public static function createPendingFromPix(
        string $userId,
        float|string $amount,
        int|string $mpPaymentId,
        ?array $mpPaymentData,
    ): SaleEntity {
        return new SaleEntity(
            id: null,
            userId: $userId,
            amount: (float) $amount,
            status: 'pending',
            mpPaymentId: (string) $mpPaymentId,
            mpPaymentData: $mpPaymentData,
        );
    }
}
