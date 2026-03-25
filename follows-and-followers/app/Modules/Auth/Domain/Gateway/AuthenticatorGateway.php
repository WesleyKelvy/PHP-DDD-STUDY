<?php

declare(strict_types=1);

namespace App\Modules\Auth\Domain\Gateway;

use App\Modules\Auth\Domain\ValueObject\LoginCredentialsValueObject;

interface AuthenticatorGateway
{
    public function attempt(LoginCredentialsValueObject $credentials): bool;

    public function currentUserEmail(): ?string;

    public function logout(): void;
}
