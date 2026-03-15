<?php

declare(strict_types=1);

namespace App\Modules\Payment\Provider;

use App\Modules\Payment\Domain\Gateway\PaymentGateway;
use App\Modules\Payment\Domain\Repo\SaleRepository;
use App\Modules\Payment\Infra\Gateways\MercadoPagoGateway;
use App\Modules\Payment\Infra\Persistence\EloquentSaleRepository;
use Illuminate\Support\ServiceProvider;
use MercadoPago\MercadoPagoConfig;

class PaymentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PaymentGateway::class, function () {
            MercadoPagoConfig::setAccessToken(config('mercadopago.access_token'));

            return new MercadoPagoGateway;
        });

        $this->app->bind(SaleRepository::class, EloquentSaleRepository::class);
    }
}
