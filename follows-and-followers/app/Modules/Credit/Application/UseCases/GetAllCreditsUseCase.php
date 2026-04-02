<?php

declare(strict_types=1);

namespace App\Modules\Credit\Application\UseCases;

use App\Modules\Credit\Domain\Repo\CreditRepository;

final class GetAllCreditsUseCase
{
    public function __construct(
        private CreditRepository $creditRepository,
    ) {}
    // TO DO
    public function execute(
        string $userId,
    ): int {
        return 0;
    }
}
