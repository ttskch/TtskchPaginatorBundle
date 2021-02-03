<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Entity;

abstract class AbstractCriteria
{
    public $page;
    public $limit;
    public $sort;
    public $direction;

    abstract public function getFormTypeClass(): ?string;
}
