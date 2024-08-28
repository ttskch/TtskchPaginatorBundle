<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Criteria;

use Ttskch\PaginatorBundle\Exception\UninitializedCriteriaException;

abstract class AbstractCriteria implements CriteriaInterface
{
    public function __construct(
        /** @readonly */
        protected string $sort,
        /** @readonly */
        protected ?int $page = null,
        /** @readonly */
        protected ?int $limit = null,
        /**
         * @var CriteriaInterface::ASC|CriteriaInterface::DESC|null
         *
         * @readonly
         */
        protected ?string $direction = null,
    ) {
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
