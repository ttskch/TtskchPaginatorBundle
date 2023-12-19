<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Doctrine;

use Doctrine\ORM\Tools\Pagination\Paginator;

class Counter extends Base
{
    public function __invoke(): int
    {
        $paginator = new Paginator($this->qb, false);

        return count($paginator);
    }
}
