<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

/**
 * @see https://tech.quartetcom.co.jp/2016/12/19/functional-testing-syfony-bundle/
 */
class WebTestCase extends BaseWebTestCase
{
    protected static function getKernelClass(): string
    {
        return TestKernel::class;
    }
}
