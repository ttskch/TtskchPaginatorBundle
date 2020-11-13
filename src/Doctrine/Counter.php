<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Doctrine;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Ttskch\PaginatorBundle\Entity\Criteria;

class Counter extends Base
{
    public function __invoke(Criteria $criteria): int
    {
        $paginator = new Paginator($this->qb, false);

        return count($paginator);
    }
}
