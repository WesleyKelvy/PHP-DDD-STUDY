<?php

declare(strict_types=1);

namespace App\Modules\Auth\Domain\Entity;

use App\Modules\Auth\Domain\Events\LoginEvent;
use App\Modules\Auth\Domain\Events\LogoutEvent;
use App\Modules\Auth\Domain\ValueObject\LoginCredentialsValueObject;
use App\Shared\Domain\Entity\DomainEntity;

final class LoginEntity extends DomainEntity
{
    public function __construct(
        private LoginCredentialsValueObject $credentials,
    ) {}

    public function loginSuccessful(
        string $ipAddress,
    ): void {
        $this->recordEvent(new LoginEvent(
            $this->credentials->email,
            $ipAddress,
        ));
    }

    public function logoutSuccessful(
        string $ipAddress,
    ): void {
        $this->recordEvent(new LogoutEvent(
            $this->credentials->email,
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
            'email'   => $this->credentials->email,
            'password'=> $this->credentials->password,
            // 'remember' => $this->credentials->remember,
        ];
    }
}
