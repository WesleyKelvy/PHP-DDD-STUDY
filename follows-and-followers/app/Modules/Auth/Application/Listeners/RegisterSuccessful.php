<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\Listeners;

use App\Modules\Auth\Domain\Events\RegisterEvent;
use App\Modules\Log\Application\DTO\CreateLogDTO;
use App\Modules\Log\Application\UseCases\CreateLogUseCase;

final class RegisterSuccessful
{
    public function __construct(private CreateLogUseCase $writeLog) {}

    public function handle(RegisterEvent $event): void
    {
        $this->writeLog->execute(new CreateLogDTO(
            action: 'register.successful',
            userId: $event->email,
            entityType: 'Register',
            ipAddress: $event->ipAddress,
            userAgent: null,
        ));
    }
}
