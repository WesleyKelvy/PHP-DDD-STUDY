<?php

declare(strict_types=1);

namespace App\Modules\Payment\Application\Events;

use App\Shared\Domain\Events\DomainEvent;
use DateTimeImmutable;

final readonly class WebhookSaleNotFoundEvent implements DomainEvent
{
    private DateTimeImmutable $occurredOn;

    public function __construct(
        public string $mpPaymentId,
        public ?string $ipAddress,
    ) {
        $this->occurredOn = new DateTimeImmutable;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
