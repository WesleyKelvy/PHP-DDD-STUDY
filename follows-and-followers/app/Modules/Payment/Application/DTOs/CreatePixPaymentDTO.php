<?php

declare(strict_types=1);

namespace App\Modules\Payment\Application\DTOs;

final readonly class CreatePixPaymentDTO
{
    public function __construct(
        public string $userId,
        public string $email,
        public string $firstName,
        public string $lastName,
        public string $docType,   // 'CPF' | 'CNPJ'
        public string $docNumber,
    ) {}

    /**
     * Build the Pix create payload.
     *
     * @return array<string, mixed>
     */
    public function toPixCreateRequest(
        float $amount,
        string $description,
        ?string $notificationUrl = null,
        ?string $externalReference = null,
    ): array {
        return [
            'transaction_amount' => $amount,
            'currency_id'        => 'BRL',
            'payment_method_id'  => 'pix',
            'description'        => $description,
            'external_reference' => $externalReference,
            'notification_url'   => $notificationUrl,
            'payer'              => $this->toPayerArray(),
        ];
    }

    /**
     * Map to the MercadoPago 'payer' payload.
     *
     * @return array<string, mixed>
     */
    public function toPayerArray(): array
    {
        return [
            'email'          => $this->email,
            'first_name'     => $this->firstName,
            'last_name'      => $this->lastName,
            'identification' => [
                'type'   => $this->docType,
                'number' => $this->docNumber,
            ],
        ];
    }
}
