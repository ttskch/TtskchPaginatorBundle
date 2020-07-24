<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Doctrine;

use Doctrine\ORM\QueryBuilder;

/**
 * @see https://www.doctrine-project.org/projects/doctrine-orm/en/latest/reference/dql-doctrine-query-language.html#first-and-max-result-items-dql-query-only
 * @see https://www.doctrine-project.org/projects/doctrine-orm/en/latest/tutorials/pagination.html
 * @see https://stackoverflow.com/questions/14884183/doctrine-querybuilder-limit-and-offset#answer-14886847
 */
abstract class Base
{
    /**
     * @var QueryBuilder
     */
    protected $qb;

    public function __construct(QueryBuilder $qb)
    {
        $this->qb = $qb;
    }
}
