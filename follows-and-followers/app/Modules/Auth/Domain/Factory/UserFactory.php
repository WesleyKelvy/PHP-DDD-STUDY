<?php

declare(strict_types=1);

namespace App\Modules\Auth\Domain\Factory;

use App\Modules\Auth\Domain\Entity\UserEntity;

final class UserFactory
{
    public static function createUser(
        string $name,
        string $email,
        ?string $password = null,
    ): UserEntity {
        return new UserEntity(
            email: $email,
            name: $name,
            password: $password,
        );
    }
}
