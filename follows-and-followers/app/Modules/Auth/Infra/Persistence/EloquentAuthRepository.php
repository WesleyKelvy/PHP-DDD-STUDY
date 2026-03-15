<?php

declare(strict_types=1);

namespace App\Modules\Auth\Infra\Persistence;

use App\Modules\Auth\Domain\Entity\UserEntity;
use App\Modules\Auth\Domain\Repo\RegisterRepository;

final class EloquentAuthRepository implements RegisterRepository
{
    public function __construct(
        private EloquentAuthModel $UserModel,
    ) {}

    public function create(UserEntity $user): UserEntity
    {
        return $this->UserModel->create($user);
    }

    public function findByUserId(string $id): ?UserEntity
    {
        return $this->UserModel->findById($id);
    }
}
