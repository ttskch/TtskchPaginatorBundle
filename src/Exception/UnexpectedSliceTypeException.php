<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Exception;

class UnexpectedSliceTypeException extends \LogicException
{
    protected $message = 'Returned value from "slicer" must be iterable';
}
