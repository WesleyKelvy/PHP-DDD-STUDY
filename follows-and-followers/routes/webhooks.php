<?php

declare(strict_types=1);

use App\Http\Controllers\Payment\WebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/mercadopago/payment-event', [WebhookController::class, 'paymentEvent'])
    ->name('mercadopago.payment-event'); // POST /webhooks/mercadopago/payment-event

Route::post('/mercadopago/notification', [WebhookController::class, 'notification'])
    ->name('mercadopago.notification');
