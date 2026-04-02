<?php

declare(strict_types=1);

namespace App\Modules\Credit\Provider;

use App\Modules\Credit\Domain\Repo\CreditRepository;
use App\Modules\Credit\Infra\Persistence\EloquentCreditModel;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CreditRepository::class, EloquentCreditModel::class);
    }
}
