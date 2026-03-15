<?php

declare(strict_types=1);

namespace App\Modules\Payment\Domain\Events;

use App\Modules\Payment\Domain\Entity\SaleEntity;
use App\Shared\Domain\Events\DomainEvent;
use DateTimeImmutable;

final readonly class SaleFailedEvent implements DomainEvent
{
    private DateTimeImmutable $occurredOn;

    public function __construct(
        public SaleEntity $sale,
    ) {
        $this->occurredOn = new DateTimeImmutable;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
