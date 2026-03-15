<?php

declare(strict_types=1);

namespace App\Shared\Application\Events;

use App\Shared\Domain\Events\DomainEvent;

interface EventDispatcherInterface
{
    public function dispatch(DomainEvent $event): void;
}
