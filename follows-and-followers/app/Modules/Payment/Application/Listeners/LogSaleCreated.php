<?php

declare(strict_types=1);

namespace App\Modules\Payment\Application\Listeners;

use App\Modules\Log\Application\DTO\CreateLogDTO;
use App\Modules\Log\Application\UseCases\CreateLogUseCase;
use App\Modules\Payment\Domain\Events\SaleCreatedEvent;

final class LogSaleCreated
{
    public function __construct(private CreateLogUseCase $writeLog) {}

    public function handle(SaleCreatedEvent $event): void
    {
        $this->writeLog->execute(new CreateLogDTO(
            action: 'sale.created',
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
