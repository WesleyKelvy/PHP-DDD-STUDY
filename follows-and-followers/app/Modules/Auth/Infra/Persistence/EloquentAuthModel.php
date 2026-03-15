<?php

declare(strict_types=1);

namespace App\Modules\Auth\Infra\Persistence;

use App\Models\user;
use App\Modules\Auth\Domain\Entity\UserEntity;
use Illuminate\Support\Facades\Hash;

final class EloquentAuthModel
{
    public function create(UserEntity $user): UserEntity
    {
        $eloquentUser = User::create([
            'name'     => $user->name,
            'email'    => $user->email,
            'password' => Hash::make($user->password),
        ]);

        return $this->toEntity($eloquentUser);
    }

    public function findById(string $id): ?UserEntity
    {
        $eloquentUser = user::query()
            ->where('id', $id);

        if (! $eloquentUser instanceof user) {
            return null;
        }

        return $this->toEntity($eloquentUser);
    }

    private function toEntity(User $user): UserEntity
    {
        return new UserEntity(
            id: $user->id,
            name: $user->name,
            email: $user->email,
        );
    }
}
