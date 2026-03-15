<?php

declare(strict_types=1);

namespace App\Modules\Auth\Domain\Events;

use App\Shared\Domain\Events\DomainEvent;
use DateTimeImmutable;

final class LogoutEvent implements DomainEvent
{
    private DateTimeImmutable $occurredOn;

    public function __construct(
        public string $email,
        public ?string $ipAddress,
    ) {
        $this->occurredOn = new DateTimeImmutable;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
