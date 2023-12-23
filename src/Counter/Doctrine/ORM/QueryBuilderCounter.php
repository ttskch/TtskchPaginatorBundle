<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Counter\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Ttskch\PaginatorBundle\Counter\CounterInterface;
use Ttskch\PaginatorBundle\Criteria\CriteriaInterface;

final class QueryBuilderCounter implements CounterInterface
{
    public function __construct(
        /** @readonly */
        private QueryBuilder $qb,
    ) {
    }

    public function count(CriteriaInterface $criteria): int
    {
        $paginator = new Paginator($this->qb, false);

        return count($paginator);
    }
}
