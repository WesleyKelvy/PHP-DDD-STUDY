<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\Listeners;

use App\Modules\Auth\Domain\Events\LoginEvent;
use App\Modules\Log\Application\DTO\CreateLogDTO;
use App\Modules\Log\Application\UseCases\CreateLogUseCase;

final class LoginSuccessful
{
    public function __construct(private CreateLogUseCase $writeLog) {}

    public function handle(LoginEvent $event): void
    {
        $this->writeLog->execute(new CreateLogDTO(
            action: 'login.successful',
            userId: $event->email,
            entityType: 'Auth',
            ipAddress: $event->ipAddress,
            userAgent: null,
        ));
    }
}
