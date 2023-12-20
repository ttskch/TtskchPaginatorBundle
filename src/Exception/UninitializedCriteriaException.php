<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Exception;

use Ttskch\PaginatorBundle\Criteria\CriteriaInterface;

class UninitializedCriteriaException extends \LogicException
{
    public function __construct(CriteriaInterface $criteria, int $code = 0, \Throwable $previous = null)
    {
        parent::__construct(sprintf('The class "%s" must not be used before initialization.', $criteria::class), $code, $previous);
    }
}
