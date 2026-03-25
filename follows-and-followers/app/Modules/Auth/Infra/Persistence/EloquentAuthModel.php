<?php

declare(strict_types=1);

namespace App\Modules\Auth\Infra\Persistence;

use App\Models\User;
use App\Modules\Auth\Domain\Entity\UserEntity;
use Illuminate\Support\Facades\Hash;

final class EloquentAuthModel
{
    public function create(UserEntity $user): UserEntity
    {
        $userData = $user->toSnapshot();

        $eloquentUser = User::create([
            'name'     => $userData->name,
            'email'    => $userData->email,
            'password' => Hash::make($userData->password),
        ]);

        return $this->toEntity($eloquentUser);
    }

    public function findById(string $id): ?UserEntity
    {
        $eloquentUser = User::query()
            ->where('id', $id)
            ->first();

        if (! $eloquentUser instanceof User) {
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
