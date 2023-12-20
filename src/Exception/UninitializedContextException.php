<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Exception;

use Ttskch\PaginatorBundle\Context;

class UninitializedContextException extends \LogicException
{
    public function __construct(int $code = 0, \Throwable $previous = null)
    {
        parent::__construct(sprintf('The class "%s" must not be used before initialization.', Context::class), $code, $previous);
    }
}
