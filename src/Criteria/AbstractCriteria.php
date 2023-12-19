<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Criteria;

use Ttskch\PaginatorBundle\Exception\UninitializedCriteriaException;

abstract class AbstractCriteria implements CriteriaInterface
{
    protected ?int $page = null;
    protected ?int $limit = null;
    protected string $sort;
    /** @var 'asc'|'desc'|null */
    protected ?string $direction = null;

    public function __construct(string $sort)
    {
        $this->sort = $sort;
    }

    public function getPage(): int
    {
        if (null === $this->page) {
            throw new UninitializedCriteriaException($this);
        }

        return $this->page;
    }

    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    public function getLimit(): int
    {
        if (null === $this->limit) {
            throw new UninitializedCriteriaException($this);
        }

        return $this->limit;
    }

    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    public function getSort(): string
    {
        return $this->sort;
    }

    public function setSort(string $sort): void
    {
        $this->sort = $sort;
    }

    public function getDirection(): string
    {
        if (null === $this->direction) {
            throw new UninitializedCriteriaException($this);
        }

        return $this->direction;
    }

    public function setDirection(string $direction): void
    {
        if (!in_array($direction, [self::ASC, self::DESC], true)) {
            throw new \InvalidArgumentException(sprintf('Invalid direction "%s" given.', $direction));
        }

        $this->direction = $direction;
    }
}
