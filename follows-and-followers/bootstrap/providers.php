<?php

declare(strict_types=1);

use App\Modules\Auth\Provider\AuthServiceProvider;
use App\Modules\Payment\Provider\PaymentServiceProvider;
use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    PaymentServiceProvider::class,
    AuthServiceProvider::class,
];
