<?php

declare(strict_types=1);

namespace App\Modules\Credit\Application\UseCases;

use App\Modules\Credit\Domain\Factory\CreditFactory;
use App\Modules\Credit\Domain\Repo\CreditRepository;
use App\Modules\Credit\Domain\ValueObject\CreditRequestValueObject;

final class CreateCreditUseCase
{
    public function __construct(
        private CreditRepository $creditRepository,
    ) {}

    public function execute(
        CreditRequestValueObject $credit,
    ): void {
        $creditEntity = CreditFactory::createCredit($credit);

        $this->creditRepository->create($creditEntity);
    }
}
