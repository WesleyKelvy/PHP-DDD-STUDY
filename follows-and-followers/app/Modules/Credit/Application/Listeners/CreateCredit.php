<?php

declare(strict_types=1);

namespace App\Modules\Credit\Application\Listeners;

use App\Modules\Credit\Application\UseCases\CreateCreditUseCase;
use App\Modules\Credit\Domain\ValueObject\CreditRequestValueObject;
use App\Modules\Payment\Domain\Events\SaleApprovedEvent;
use InvalidArgumentException;

final readonly class CreateCreditListener
{
    public function __construct(private CreateCreditUseCase $useCase) {}

    public function handle(SaleApprovedEvent $event): void
    {
        if ($event->saleId === null) {
            throw new InvalidArgumentException(
                'Cannot create credit for a sale without an ID.',
            );
        }

        $creditRequest = new CreditRequestValueObject(
            userId: $event->userId,
            saleId: $event->saleId,
            total: (int) $event->amount / 100 ?? null,
            used: 0,
            reserved: 0,
            expiresAt: null,
        );

        $this->useCase->execute($creditRequest);
    }
}
