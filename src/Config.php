<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle;

use Ttskch\PaginatorBundle\Criteria\CriteriaInterface;

/**
 * @final
 *
 * @internal
 */
class Config
{
    public string $pageName;
    public int $pageRange;
    public string $limitName;
    public int $limitDefault;
    public string $sortKeyName;
    public string $sortDirectionName;
    /** @var CriteriaInterface::ASC|CriteriaInterface::DESC */
    public string $sortDirectionDefault;
    public string $templatePager;
    public string $templateSortable;
}
