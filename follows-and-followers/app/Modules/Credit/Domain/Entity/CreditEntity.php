<?php

declare(strict_types=1);

namespace App\Modules\Credit\Domain\Entity;

use App\Shared\Domain\Entity\DomainEntity;
use DateTime;

final class CreditEntity extends DomainEntity
{
    /**
     * @param  array<string, mixed>|null  $mpPaymentData
     */
    public function __construct(
        private ?string $id,
        private string $userId,
        private string $saleId,
        private int $total,
        private int $used,
        private int $reserved,
        private ?DateTime $expiresAt,
    ) {}

    public function available(): int
    {
        return $this->total - $this->used - $this->reserved;
    }

    public function hasAvailable(): bool
    {
        return $this->available() > 0;
    }

    public function reserve(int $amount = 1): void
    {
        if ($this->available() < $amount) {
            throw new \DomainException('Not enough credits');
        }

        $this->reserved += $amount;
    }

    public function consume(int $amount = 1): void
    {
        if ($this->reserved < $amount) {
            throw new \DomainException('Nothing reserved to consume');
        }

        $this->reserved -= $amount;
        $this->used += $amount;
    }

    public function release(int $amount = 1): void
    {
        if ($this->reserved < $amount) {
            throw new \DomainException('Invalid release');
        }

        $this->reserved -= $amount;
    }

    public function toPersistenceArray(): array
    {
        return [
            'user_id'   => $this->userId,
            'sale_id'   => $this->saleId,
            'total'     => $this->total,
            'used'      => $this->used,
            'reserved'  => $this->reserved,
            'expires_at'=> $this->expiresAt ?? null,
        ];
    }
}
