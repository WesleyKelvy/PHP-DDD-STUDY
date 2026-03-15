<?php

declare(strict_types=1);

namespace App\Modules\Log\Application\UseCases;

use App\Modules\Log\Application\DTO\CreateLogDTO;
use App\Modules\Log\Domain\Entity\LogEntity;
use App\Modules\Log\Domain\Repo\LogRepository;

final class CreateLogUseCase
{
    public function __construct(private LogRepository $logRepo) {}

    public function execute(CreateLogDTO $data): void
    {
        $this->logRepo->write(
            new LogEntity(
                userId: $data->userId,
                action: $data->action,
                entityType: $data->entityType,
                entityId: $data->entityId,
                ipAddress: $data->ipAddress,
                userAgent: $data->userAgent,
                payload: $data->payload,
            ),
        );
    }
}
