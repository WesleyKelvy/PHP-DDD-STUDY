<?php

declare(strict_types=1);

namespace App\Modules\Log\Application\DTO;

final readonly class CreateLogDTO
{
    /**
     * @param  array<string, mixed>|null  $payload
     */
    public function __construct(
        public string $action,
        public ?string $userId,
        public ?string $ipAddress,
        public ?string $userAgent,
        public ?string $entityType = null,
        public ?string $entityId = null,
        public ?array $payload = null,
    ) {}
}
