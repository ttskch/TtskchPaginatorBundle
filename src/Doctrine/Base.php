<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Doctrine;

use Doctrine\ORM\QueryBuilder;

abstract class Base
{
    public function __construct(protected QueryBuilder $qb)
    {
    }
}
