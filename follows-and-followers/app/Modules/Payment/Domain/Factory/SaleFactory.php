<?php

declare(strict_types=1);

namespace App\Modules\Payment\Domain\Factory;

use App\Modules\Payment\Domain\Entity\SaleEntity;

final class SaleFactory
{
    public static function createPendingFromPix(
        string $userId,
        float $amount,
        string $mpPaymentId,
        ?array $mpPaymentData,
    ): SaleEntity {
        return new SaleEntity(
            id: null,
            userId: $userId,
            amount: $amount,
            status: 'pending',
            mpPaymentId: $mpPaymentId,
            mpPaymentData: $mpPaymentData,
        );
    }
}
