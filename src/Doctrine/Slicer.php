<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Doctrine;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Ttskch\PaginatorBundle\Entity\Criteria;

class Slicer extends Base
{
    const ALIAS_PREFIX = 'ttskch_paginator_bundle';

    public function __invoke(Criteria $criteria, bool $alreadyJoined = false): \ArrayIterator
    {
        $this->sortByCriteria($criteria, $alreadyJoined);

        $paginator = new Paginator($this->qb, false);

        $paginator->getQuery()
            ->setFirstResult($criteria->limit * ($criteria->page - 1))
            ->setMaxResults($criteria->limit)
        ;

        return $paginator->getIterator();
    }

    private function sortByCriteria(Criteria $criteria, bool $alreadyJoined): void
    {
        if (!$criteria->sort) {
            return;
        }

        if ($alreadyJoined) {
            $this->qb->orderBy($criteria->sort, $criteria->direction);

            return;
        }

        $columns = explode('.', $criteria->sort);

        $alias = $this->qb->getRootAliases()[0];
        $sortColumn = end($columns);
        $columnsToJoin = array_slice($columns, 0, -1);

        foreach ($columnsToJoin as $i => $column) {
            $this->qb->leftJoin(sprintf('%s.%s', $alias, $column), $alias = sprintf('%s_%s', self::ALIAS_PREFIX, $i));
        }

        $this->qb->orderBy(sprintf('%s.%s', $alias, $sortColumn), $criteria->direction);
    }
}
