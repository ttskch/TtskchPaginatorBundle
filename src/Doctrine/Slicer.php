<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Doctrine;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Ttskch\PaginatorBundle\Criteria\CriteriaInterface;

/**
 * @see https://www.doctrine-project.org/projects/doctrine-orm/en/latest/reference/dql-doctrine-query-language.html#first-and-max-result-items-dql-query-only
 * @see https://www.doctrine-project.org/projects/doctrine-orm/en/latest/tutorials/pagination.html
 * @see https://stackoverflow.com/questions/14884183/doctrine-querybuilder-limit-and-offset#answer-14886847
 */
class Slicer extends Base
{
    public const ALIAS_PREFIX = 'ttskch_paginator_bundle';

    /**
     * @return \Traversable<array-key, mixed>
     */
    public function __invoke(CriteriaInterface $criteria, bool $alreadyJoined = false): \Traversable
    {
        $this->sortByCriteria($criteria, $alreadyJoined);

        // @see https://stackoverflow.com/questions/50199102/setmaxresults-does-not-works-fine-when-doctrine-query-has-join/50203939#answer-50203939
        $paginator = new Paginator($this->qb, true);

        $paginator->getQuery()
            ->setFirstResult($criteria->getLimit() * ($criteria->getPage() - 1))
            ->setMaxResults($criteria->getLimit())
        ;

        return $paginator->getIterator();
    }

    private function sortByCriteria(CriteriaInterface $criteria, bool $alreadyJoined): void
    {
        if ($alreadyJoined) {
            $this->qb->orderBy($criteria->getSort(), $criteria->getDirection());

            return;
        }

        $columns = explode('.', $criteria->getSort());

        $alias = $this->qb->getRootAliases()[0] ?? null;

        if (null === $alias) {
            throw new \RuntimeException('Root alias not found.');
        }

        $sortColumn = end($columns);
        $columnsToJoin = array_slice($columns, 0, -1);

        foreach ($columnsToJoin as $i => $column) {
            $this->qb->leftJoin(sprintf('%s.%s', $alias, $column), $alias = sprintf('%s_%s', self::ALIAS_PREFIX, $i));
        }

        $this->qb->orderBy(sprintf('%s.%s', $alias, $sortColumn), $criteria->getDirection());
    }
}
