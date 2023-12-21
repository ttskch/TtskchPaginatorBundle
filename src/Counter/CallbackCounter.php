<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Counter;

use Ttskch\PaginatorBundle\Criteria\CriteriaInterface;

/**
 * @template TCriteria of CriteriaInterface
 */
final class CallbackCounter implements CounterInterface
{
    /**
     * @param \Closure(TCriteria $criteria): int $callback
     */
    public function __construct(
        /** @readonly */
        private \Closure $callback,
    ) {
    }

    public function count(CriteriaInterface $criteria): int
    {
        return call_user_func($this->callback, $criteria);
    }
}
