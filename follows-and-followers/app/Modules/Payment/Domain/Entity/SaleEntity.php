<?php

declare(strict_types=1);

namespace App\Modules\Payment\Domain\Entity;

use App\Modules\Payment\Domain\Events\SaleApprovedEvent;
use App\Modules\Payment\Domain\Events\SaleCreatedEvent;
use App\Modules\Payment\Domain\Events\SaleFailedEvent;
use App\Shared\Domain\Entity\DomainEntity;

final class SaleEntity extends DomainEntity
{
    /**
     * @param  array<string, mixed>|null  $mpPaymentData
     */
    public function __construct(
        private ?string $id,
        private string $userId,
        private float $amount,
        private string $status,
        private string $mpPaymentId,
        private ?array $mpPaymentData,
    ) {}

    public function markAsCreated(
        string $mpPaymentId,
        string $status,
        ?string $ipAddress,
        // Status on created in already 'pending'
    ): void {
        $this->recordEvent(new SaleCreatedEvent(
            $this,
            $mpPaymentId,
            $status,
            $ipAddress,
        ));
    }

    public function approve(
        string $mpPaymentId,
        ?string $ipAddress,
    ): void {
        if ($this->isApproved()) {
            return;
        }

        $this->status = 'approved';

        $this->recordEvent(new SaleApprovedEvent(
            $this->id,
            $this->userId,
            $this->amount,
            $mpPaymentId,
            $this->status,
            $ipAddress,
        ));
    }

    /**
     * Behavior: To fail the sale.
     */
    public function fail(
        string $mpPaymentId,
        ?string $ipAddress,
    ): void {
        $this->status = 'failed';

        $this->recordEvent(new SaleFailedEvent(
            $this->id,
            $this->userId,
            $mpPaymentId,
            $this->status,
            $ipAddress,
        ));
    }

    /**
     * Behavior: To cancel the sale
     */
    // public function cancel(array $gatewayData): void
    // {
    //     $this->status = 'canceled';
    //     $this->mpPaymentData = $gatewayData;
    //     // $this->domainEvents[] = new SaleCanceledEvent($this);
    // }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Helper: Parsing camel case to db snake case attributes.
     *
     * @return array<string, mixed>
     */
    public function toPersistenceArray(): array
    {
        return [
            'user_id'         => $this->userId,
            'amount'          => $this->amount,
            'status'          => $this->status,
            'mp_payment_id'   => $this->mpPaymentId,
            'mp_payment_data' => $this->mpPaymentData,
        ];
    }
}
