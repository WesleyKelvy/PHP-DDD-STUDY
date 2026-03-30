<?php

declare(strict_types=1);

namespace App\Modules\Payment\Application\Listeners;

use App\Modules\Log\Application\DTO\CreateLogDTO;
use App\Modules\Log\Application\UseCases\CreateLogUseCase;
use App\Modules\Payment\Domain\Events\SaleApprovedEvent;
use App\Modules\Payment\Domain\Events\SaleFailedEvent;

final class LogSaleUpdated
{
    public function __construct(private CreateLogUseCase $writeLog) {}

    public function handle(SaleApprovedEvent|SaleFailedEvent $event): void
    {
        $this->writeLog->execute(new CreateLogDTO(
            action: 'sale.updated.' . $event->status . '&credit.created',
            userId: $event->sale->userId,
            entityType: 'Sale',
            entityId: $event->sale->id,
            ipAddress: $event->ipAddress,
            userAgent: null,
            payload: [
                'sale_id'       => $event->sale->id,
                'mp_payment_id' => $event->mpPaymentId,
                'status'        => $event->status,
            ],
        ));
    }
}
