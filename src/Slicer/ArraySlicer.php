<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Slicer;

use Ttskch\PaginatorBundle\Criteria\CriteriaInterface;

/**
 * @template-covariant T
 *
 * @implements SlicerInterface<array<T>>
 */
final class ArraySlicer implements SlicerInterface
{
    /**
     * @param array<T> $array
     */
    public function __construct(
        /** @readonly */
        private array $array,
    ) {
    }

    /**
     * @return array<T>
     */
    public function slice(CriteriaInterface $criteria): array
    {
        return array_slice($this->array, $criteria->getLimit() * ($criteria->getPage() - 1), $criteria->getLimit());
    }
}
