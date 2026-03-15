<?php

declare(strict_types=1);

namespace App\Modules\Auth\Domain\Entity;

use App\Modules\Auth\Domain\Events\RegisterEvent;
use App\Shared\Domain\Entity\DomainEntity;

final class UserEntity extends DomainEntity
{
    public function __construct(
        public string $email,
        public string $name,
        public ?string $id = null,
        public ?string $password = null,
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
     * Helper: Parsing camel case to db snake case attributes.
     *
     * @return array<string, mixed>
     */
    public function toPersistenceArray(): array
    {
        return [
            'name'    => $this->name,
            'email'   => $this->email,
            'password'=> $this->password,
        ];
    }
}
