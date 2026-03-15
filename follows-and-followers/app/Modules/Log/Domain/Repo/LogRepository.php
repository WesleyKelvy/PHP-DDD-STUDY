<?php

declare(strict_types=1);

namespace App\Modules\Log\Domain\Repo;

use App\Modules\Log\Domain\Entity\LogEntity;

interface LogRepository
{
    public function write(LogEntity $data): void;
}
