<?php

declare(strict_types=1);

use App\Modules\Auth\Provider\AuthServiceProvider;
use App\Modules\Payment\Provider\PaymentServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            // Auth
            Route::middleware('web')
                ->prefix('auth')
                ->as('auth.')
                ->group(base_path('routes/auth.php'));

            // Payments (auth required)
            Route::middleware(['web', 'auth'])
                ->prefix('payments')
                ->as('payments.')
                ->group(base_path('routes/payments.php'));

            // Webhooks (stateless)
            Route::middleware('web')
                ->prefix('webhooks')
                ->as('webhooks.')
                ->group(base_path('routes/webhooks.php'));
        },
    )
    ->withProviders([PaymentServiceProvider::class, AuthServiceProvider::class])
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
