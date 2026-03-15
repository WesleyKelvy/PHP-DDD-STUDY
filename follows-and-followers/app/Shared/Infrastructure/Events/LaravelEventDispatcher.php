<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Events;

use App\Shared\Application\Events\EventDispatcherInterface;
use App\Shared\Domain\Events\DomainEvent;
use Illuminate\Contracts\Events\Dispatcher as LaravelDispatcher;

final readonly class LaravelEventDispatcher implements EventDispatcherInterface
{
    /**
     * Injetamos o dispatcher nativo do Laravel
     */
    public function __construct(
        private LaravelDispatcher $laravelDispatcher,
    ) {}

    /**
     * Implementação da nossa "Porta"
     */
    public function dispatch(DomainEvent $event): void
    {
        // O Laravel aceita objetos puros como eventos.
        // Aqui nós repassamos o nosso Domain Event para o barramento do framework.
        $this->laravelDispatcher->dispatch($event);
    }
}
