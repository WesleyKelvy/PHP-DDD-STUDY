<?php

declare(strict_types=1);

namespace App\Modules\Auth\Domain\Repo;

use App\Modules\Auth\Domain\Entity\UserEntity;

interface RegisterRepository
{
    public function create(UserEntity $user): UserEntity;

    public function findByUserId(string $id): ?UserEntity;
}
