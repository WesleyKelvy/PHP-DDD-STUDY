<?php

declare(strict_types=1);

use App\Modules\Credit\Domain\Entity\CreditEntity;

final class CreditEntityCollection
{
    /** @var CreditEntity[] */
    private array $items;

    public function __construct(CreditEntity ...$items)
    {
        $this->items = $items;
    }

    /** @return CreditEntity[] */
    public function all(): array
    {
        return $this->items;
    }

    public function first(): ?CreditEntity
    {
        return $this->items[0] ?? null;
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }

    /** @param CreditEntity[] $items */
    public static function fromArray(array $items): self
    {
        return new self(...$items);
    }
}
