<?php

declare(strict_types=1);

namespace App\Shared\Domain\Events;

use DateTimeImmutable;

interface DomainEvent
{
    public function occurredOn(): DateTimeImmutable;
}
