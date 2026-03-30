<?php

declare(strict_types=1);

namespace App\Modules\Credit\Provider;

use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // $this->app->singleton(PaymentGateway::class, function () {
        //     MercadoPagoConfig::setAccessToken(config('mercadopago.access_token'));

        //     return new MercadoPagoGateway;
        // });

        // $this->app->bind(SaleRepository::class, EloquentSaleRepository::class);
    }
}
