<?php

declare(strict_types=1);

namespace App\Modules\Payment\Application\Listeners;

use App\Modules\Log\Application\DTO\CreateLogDTO;
use App\Modules\Log\Application\UseCases\CreateLogUseCase;
use App\Modules\Payment\Application\Events\WebhookSaleNotFoundEvent;

final class LogWebhookSaleNotFound
{
    public function __construct(private CreateLogUseCase $writeLog) {}

    public function handle(WebhookSaleNotFoundEvent $event): void
    {
        $this->writeLog->execute(new CreateLogDTO(
            action: 'webhook.sale.not.found',
            userId: null,
            entityType: 'Sale',
            entityId: null,
            ipAddress: $event->ipAddress,
            userAgent: null,
            payload: ['mp_payment_id' => $event->mpPaymentId],
        ));
    }
}
