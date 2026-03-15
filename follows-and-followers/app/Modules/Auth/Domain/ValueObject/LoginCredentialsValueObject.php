<?php

declare(strict_types=1);

namespace App\Modules\Auth\Domain\ValueObject;

final readonly class LoginCredentialsValueObject
{
    public function __construct(
        public string $email,
        public string $password,
        public bool $remember = false,
    ) {}
}
