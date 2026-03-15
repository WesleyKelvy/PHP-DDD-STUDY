<?php

declare(strict_types=1);

namespace App\Modules\Payment\Application\DTOs;

final readonly class CreatePaymentMercadoPagoResponseDTO
{
    /**
     * @param  array<string, mixed>|null  $pixTransactionData
     */
    public function __construct(
        public int $id,
        public string $status,
        public ?array $pixTransactionData,
    ) {}

    public static function fromMercadoPago(object $response): self
    {
        $txData = $response->pointOfInteraction?->transactionData ?? null;

        return new self(
            id: $response->id,
            status: $response->status,
            pixTransactionData: $txData ? (array) $txData : null,
        );
    }
}
