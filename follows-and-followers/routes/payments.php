<?php

declare(strict_types=1);

use App\Modules\Payment\Presentation\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::post('/', [PaymentController::class, 'create'])
    ->name('create');

Route::get('/{sale}/pending', [PaymentController::class, 'pending'])
    ->name('pending');

Route::get('/{sale}/success', [PaymentController::class, 'success'])
    ->name('success');
