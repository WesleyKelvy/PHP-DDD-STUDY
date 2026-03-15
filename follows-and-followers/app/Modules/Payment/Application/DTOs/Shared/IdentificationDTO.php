<?php

declare(strict_types=1);

namespace App\Modules\Payment\Application\DTOs\Shared;

final readonly class IdentificationDTO
{
    public function __construct(
        public ?string $type,
        public ?string $number,
    ) {}

    public static function fromMercadoPago(object $identification): self
    {
        return new self(
            type: $identification->type ?? null,
            number: $identification->number ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'type'   => $this->type,
            'number' => $this->number,
        ];
    }
}
