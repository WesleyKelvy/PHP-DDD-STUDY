<?php

declare(strict_types=1);

namespace App\Modules\Auth\Infra\Gateway;

use App\Models\User;
use App\Modules\Auth\Domain\Gateway\AuthenticatorGateway;
use App\Modules\Auth\Domain\ValueObject\LoginCredentialsValueObject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LaravelAuth implements AuthenticatorGateway
{
    public function attempt(LoginCredentialsValueObject $credentials): bool
    {
        $user = User::where(
            'email_hmac',
            hash_hmac(
                'sha256',
                strtolower($credentials->email),
                config('app.email_hash_key'),
            ),
        )->first();

        if ($user && Hash::check($credentials->password, $user->password)) {
            Auth::login($user, $credentials->remember);

            return true;
        }

        return false;
    }

    public function currentUserEmail(): ?string
    {
        return Auth::user()?->email;
    }

    public function logout(): void
    {
        Auth::logout();
    }
}
