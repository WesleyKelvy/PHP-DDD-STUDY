<?php

declare(strict_types=1);

namespace App\Modules\Payment\Application\DTOs\Shared;

final readonly class PayerDTO
{
    public function __construct(
        public string $email,
        public IdentificationDTO $identification,
    ) {}

    public static function fromMercadoPago(object $payer): self
    {
        return new self(
            email: $payer->email ?? '',
            identification: IdentificationDTO::fromMercadoPago($payer->identification ?? (object) []),
        );
    }

    public function toArray(): array
    {
        return [
            'email'          => $this->email,
            'identification' => $this->identification->toArray(),
        ];
    }
}
