<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\UseCases;

use App\Modules\Auth\Domain\Factory\UserFactory;
use App\Modules\Auth\Domain\Repo\RegisterRepository;
use App\Modules\Auth\Domain\ValueObject\RegisterValueObject;
use App\Shared\Application\Events\EventDispatcherInterface;

final class RegisterUseCase
{
    public function __construct(
        private RegisterRepository $registerRepository,
        private EventDispatcherInterface $eventDispatcher,
    ) {}

    public function execute(
        RegisterValueObject $data,
        string $ipAddress,
    ): bool {
        $userEntity = UserFactory::createUser(
            name: $data->name,
            email: $data->email,
            password: $data->password,
        );

        $response = $this->registerRepository->create($userEntity);

        if ($response) {
            $userEntity->registerSuccessful($ipAddress);

            foreach ($userEntity->pullDomainEvents() as $event) {
                $this->eventDispatcher->dispatch($event);
            }

            return true;
        }

        return false;
    }
}
