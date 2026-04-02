<?php

declare(strict_types=1);

namespace App\Modules\Payment\Domain\Events;

use App\Shared\Domain\Events\DomainEvent;
use DateTimeImmutable;

final readonly class SaleFailedEvent implements DomainEvent
{
    private DateTimeImmutable $occurredOn;

    public function __construct(
        public string $saleId,
        public string $userId,
        public string $mpPaymentId,
        public string $status,
        public ?string $ipAddress,
    ) {
        $this->occurredOn = new DateTimeImmutable;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
