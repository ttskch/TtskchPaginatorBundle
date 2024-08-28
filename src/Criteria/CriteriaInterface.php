<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Criteria;

use Symfony\Component\Form\FormTypeInterface;

interface CriteriaInterface
{
    public const ASC = 'asc';
    public const DESC = 'desc';

    public function getPage(): int;

    public function setPage(int $page): void;

    public function getLimit(): int;

    public function setLimit(int $limit): void;

    public function getSort(): string;

    public function setSort(string $sort): void;

    /**
     * @return self::ASC|self::DESC
     */
    public function getDirection(): string;

    /**
     * @param self::ASC|self::DESC $direction
     */
    public function setDirection(string $direction): void;

    /**
     * @return class-string<FormTypeInterface>
     */
    public function getFormTypeClass(): string;
}
