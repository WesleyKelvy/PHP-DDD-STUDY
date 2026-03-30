<?php

declare(strict_types=1);

namespace App\Modules\Payment\Domain\ValueObject;

final readonly class PixPaymentRequest
{
    public function __construct(
        public string $userId,
        public string $email,
        public string $firstName,
        public string $lastName,
        public string $docType,
        public string $docNumber,
        public float $amount,
    ) {}
}
