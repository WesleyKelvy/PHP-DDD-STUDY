<?php

declare(strict_types=1);

namespace App\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCSRFToken extends Middleware
{
    protected $except = [
        'webhooks/mercadopago/*',
    ];
}
