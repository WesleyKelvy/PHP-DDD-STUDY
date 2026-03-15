<?php

declare(strict_types=1);

namespace App\Modules\Log\Domain\Entity;

class LogEntity
{
    public function __construct(
        public ?int $userId,
        public string $action,
        public ?string $entityType,
        public ?int $entityId,
        public ?string $ipAddress,
        public ?string $userAgent = null,
        public ?array $payload = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toPersistenceArray(): array
    {
        return [
            'user_id'    => $this->userId,
            'action'     => $this->action,
            'entity_type'=> $this->entityType,
            'entity_id'  => $this->entityId,
            'ip_address' => $this->ipAddress,
            'user_agent' => $this->userAgent,
            'payload'    => $this->payload,
        ];
    }
}
