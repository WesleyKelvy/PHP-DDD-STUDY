<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\UseCases;

use App\Modules\Auth\Domain\Entity\LoginEntity;
use App\Modules\Auth\Domain\Gateway\AuthenticatorGateway;
use App\Modules\Auth\Domain\ValueObject\LoginCredentialsValueObject;
use App\Shared\Application\Events\EventDispatcherInterface;

final class LoginUseCase
{
    public function __construct(
        private AuthenticatorGateway $authenticator,
        private EventDispatcherInterface $eventDispatcher,
    ) {}

    public function execute(
        LoginCredentialsValueObject $credentials,
        string $ipAddress,
    ): bool {
        $loginEntity = new LoginEntity($credentials);

        $response = $this->authenticator->attempt($loginEntity);

        if ($response) {
            $loginEntity->loginSuccessful($ipAddress);

            foreach ($loginEntity->pullDomainEvents() as $event) {
                $this->eventDispatcher->dispatch($event);
            }
        }

        return $response;
    }
}
