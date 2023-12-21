<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Slicer;

use Ttskch\PaginatorBundle\Criteria\CriteriaInterface;

/**
 * @template-covariant TSlice
 */
interface SlicerInterface
{
    /**
     * @return TSlice
     */
    public function slice(CriteriaInterface $criteria): mixed;
}
