<?php

declare(strict_types=1);

namespace App\Modules\Auth\Domain\Entity;

use App\Modules\Auth\Domain\Events\RegisterEvent;
use App\Modules\Auth\Domain\ValueObject\UserSnapshot;
use App\Shared\Domain\Entity\DomainEntity;

final class UserEntity extends DomainEntity
{
    public function __construct(
        private string $email,
        private string $name,
        private ?string $id = null,
        private ?string $password = null,
    ) {}

    public function registerSuccessful(
        string $ipAddress,
    ): void {
        $this->recordEvent(new RegisterEvent(
            $this->email,
            $ipAddress,
        ));
    }

    /**
     * Exporta o estado da Entidade de forma imutável e tipada.
     */
    public function toSnapshot(): UserSnapshot
    {
        return new UserSnapshot(
            name: $this->name,
            email: $this->email,
            password: $this->password,
        );
    }
}
