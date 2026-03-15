<?php

declare(strict_types=1);

namespace App\Modules\Payment\Domain\Gateway;

use App\Modules\Payment\Domain\ValueObject\PixPaymentRequest;

interface PaymentGateway
{
    public function createPixPayment(PixPaymentRequest $request): object;

    public function fetchPayment(int $paymentId): object;
}
