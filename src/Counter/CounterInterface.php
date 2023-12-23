<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Counter;

use Ttskch\PaginatorBundle\Criteria\CriteriaInterface;

interface CounterInterface
{
    public function count(
        /* @readonly */
        CriteriaInterface $criteria,
    ): int;
}
