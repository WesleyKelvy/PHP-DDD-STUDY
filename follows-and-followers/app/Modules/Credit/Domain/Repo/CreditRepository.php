<?php

declare(strict_types=1);

namespace App\Modules\Credit\Domain\Repo;

use App\Modules\Credit\Domain\CreditEntityCollection;
use App\Modules\Credit\Domain\Entity\CreditEntity;

interface CreditRepository
{
    public function create(CreditEntity $credit): void;

    public function getAllByUserId(string $userId): CreditEntityCollection;

    public function findBySaleId(string $saleid): ?CreditEntity;

    public function update(CreditEntity $sale): void;
}
