<?php

declare(strict_types=1);

namespace App\Modules\Credit\Domain\ValueObject;

use DateTime;

final readonly class CreditRequestValueObject
{
    public function __construct(
        public string $userId,
        public string $saleId,
        public int $total,
        public int $used,
        public int $reserved,
        public ?DateTime $expiresAt,
    ) {}
}
