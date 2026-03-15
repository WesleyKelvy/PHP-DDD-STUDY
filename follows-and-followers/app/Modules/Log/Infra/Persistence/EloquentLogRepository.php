<?php

declare(strict_types=1);

namespace App\Modules\Log\Infra\Persistence;

use App\Modules\Log\Domain\Entity\LogEntity;
use App\Modules\Log\Domain\Repo\LogRepository;

final class EloquentLogRepository implements LogRepository
{
    public function __construct(private EloquentLogModel $logModel) {}

    public function write(LogEntity $log): void
    {
        $this->logModel->create($log->toPersistenceArray());
    }
}
