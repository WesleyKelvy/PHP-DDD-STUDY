<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\UseCases;

use App\Modules\Auth\Domain\Events\LoginEvent;
use App\Modules\Auth\Domain\Gateway\AuthenticatorGateway;
use App\Modules\Auth\Domain\ValueObject\LoginCredentialsValueObject;
use App\Shared\Application\Events\EventDispatcherInterface;

final readonly class LoginUseCase
{
    public function __construct(
        private AuthenticatorGateway $authenticator,
        private EventDispatcherInterface $eventDispatcher,
    ) {}

    public function execute(
        LoginCredentialsValueObject $credentials,
        string $ipAddress,
    ): bool {
        $isAuthenticated = $this->authenticator->attempt($credentials);

        if (! $isAuthenticated) {
            return false;
        }
        $event = new LoginEvent(
            email: $credentials->email,
            ipAddress: $ipAddress,
        );

        $this->eventDispatcher->dispatch($event);

        return true;
    }
}
