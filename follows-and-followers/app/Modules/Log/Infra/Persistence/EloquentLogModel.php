<?php

declare(strict_types=1);

namespace App\Modules\Log\Infra\Persistence;

use App\Models\Log;

final class EloquentLogModel
{
    public function create(array $log): void
    {
        Log::create($log);
    }
}
