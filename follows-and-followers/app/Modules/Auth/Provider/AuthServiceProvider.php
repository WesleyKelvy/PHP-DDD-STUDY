<?php

declare(strict_types=1);

namespace App\Modules\Auth\Provider;

use App\Modules\Auth\Domain\Gateway\AuthenticatorGateway;
use App\Modules\Auth\Domain\Repo\RegisterRepository;
use App\Modules\Auth\Infra\Gateway\LaravelAuth;
use App\Modules\Auth\Infra\Persistence\EloquentAuthRepository;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(RegisterRepository::class, EloquentAuthRepository::class);
        $this->app->bind(AuthenticatorGateway::class, LaravelAuth::class);
    }
}
