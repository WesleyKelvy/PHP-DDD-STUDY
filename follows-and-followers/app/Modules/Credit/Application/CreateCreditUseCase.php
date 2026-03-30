<?php

declare(strict_types=1);

namespace App\Modules\Credit\Application\UseCases;

use App\Modules\Credit\Domain\Repo\CreditRepository;

final class CreateCreditUseCase
{
    public function __construct(
        private CreditRepository $creditRepository,
    ) {}

    public function execute(
        CreditvalueObject $credit,
    ): void {}
}
