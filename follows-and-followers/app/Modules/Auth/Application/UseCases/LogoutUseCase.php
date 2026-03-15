<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\UseCases;

use App\Modules\Auth\Domain\Events\LogoutEvent;
use App\Modules\Auth\Domain\Gateway\AuthenticatorGateway;
use App\Shared\Application\Events\EventDispatcherInterface;

final class LogoutUseCase
{
    public function __construct(
        private AuthenticatorGateway $authenticator,
        private EventDispatcherInterface $eventDispatcher,
    ) {}

    public function execute(
        string $ipAddress,
    ): void {
        $email = $this->authenticator->currentUserEmail();

        $this->authenticator->logout();

        if ($email !== null && $email !== '') {
            $this->eventDispatcher->dispatch(
                new LogoutEvent($email, $ipAddress),
            );
        }
    }
}
