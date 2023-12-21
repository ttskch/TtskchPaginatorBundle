<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Slicer;

use Ttskch\PaginatorBundle\Criteria\CriteriaInterface;

/**
 * @template-covariant TSlice
 * @template-covariant TCriteria of CriteriaInterface
 *
 * @implements SlicerInterface<TSlice>
 */
final class CallbackSlicer implements SlicerInterface
{
    /**
     * @param \Closure(TCriteria $criteria): TSlice $callback
     */
    public function __construct(
        /** @readonly */
        private \Closure $callback,
    ) {
    }

    /**
     * @return TSlice
     */
    public function slice(CriteriaInterface $criteria): mixed
    {
        return call_user_func($this->callback, $criteria);
    }
}
