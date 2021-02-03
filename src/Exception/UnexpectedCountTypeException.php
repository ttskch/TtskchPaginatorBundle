<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Exception;

class UnexpectedCountTypeException extends \LogicException
{
    protected $message = 'Returned value from "counter" must be an integer';
}
