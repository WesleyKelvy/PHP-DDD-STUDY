<?php

declare(strict_types=1);

namespace App\Modules\Auth\Infra\Gateway;

use App\Modules\Auth\Domain\Entity\LoginEntity;
use App\Modules\Auth\Domain\Gateway\AuthenticatorGateway;
use Illuminate\Support\Facades\Auth;

class LaravelAuth implements AuthenticatorGateway
{
    public function attempt(LoginEntity $credentials): bool
    {
        return Auth::attempt($credentials->toPersistenceArray());
    }

    public function currentUserEmail(): ?string
    {
        $email = Auth::user()?->email;

        return is_string($email) ? $email : null;
    }

    public function logout(): void
    {
        Auth::logout();
    }
}
