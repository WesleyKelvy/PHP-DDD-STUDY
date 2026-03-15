<?php

declare(strict_types=1);

namespace App\Modules\Auth\Domain\Gateway;

use App\Modules\Auth\Domain\Entity\LoginEntity;

interface AuthenticatorGateway
{
    public function attempt(LoginEntity $data): bool;

    public function currentUserEmail(): ?string;

    public function logout(): void;
}
