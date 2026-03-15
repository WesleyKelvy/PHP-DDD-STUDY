<?php

declare(strict_types=1);

namespace App\Modules\Payment\Domain\Events;

use App\Modules\Payment\Domain\Entity\SaleEntity;
use App\Shared\Domain\Events\DomainEvent;
use DateTimeImmutable;

final readonly class SaleApprovedEvent implements DomainEvent
{
    private DateTimeImmutable $occurredOn;

    public function __construct(
        public SaleEntity $sale,
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
