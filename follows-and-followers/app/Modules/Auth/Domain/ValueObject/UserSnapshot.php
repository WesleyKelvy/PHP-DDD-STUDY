<?php

declare(strict_types=1);

namespace App\Modules\Auth\Domain\ValueObject;

final readonly class UserSnapshot
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $password,
    ) {}
}
