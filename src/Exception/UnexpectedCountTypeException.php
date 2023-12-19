<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Exception;

class UnexpectedCountTypeException extends \LogicException
{
    public function __construct(int $code = 0, \Throwable $previous = null)
    {
        parent::__construct('Returned value from "counter" must be an integer.', $code, $previous);
    }
}
