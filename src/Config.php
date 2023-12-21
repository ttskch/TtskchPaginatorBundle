<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle;

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
    /** @var 'asc'|'desc' */
    public string $sortDirectionDefault;
    public string $templatePager;
    public string $templateSortable;
}
