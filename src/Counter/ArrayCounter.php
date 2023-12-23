<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Counter;

use Ttskch\PaginatorBundle\Criteria\CriteriaInterface;

final class ArrayCounter implements CounterInterface
{
    /**
     * @param array<mixed> $array
     */
    public function __construct(
        /** @readonly */
        private array $array,
    ) {
    }

    public function count(CriteriaInterface $criteria): int
    {
        return count($this->array);
    }
}
