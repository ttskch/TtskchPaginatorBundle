<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Exception;

use Ttskch\PaginatorBundle\Paginator;

class UninitializedPaginatorException extends \LogicException
{
    public function __construct(int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct(sprintf('The class "%s" must not be used before initialization.', Paginator::class), $code, $previous);
    }
}
