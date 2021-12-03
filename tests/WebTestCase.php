<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Component\HttpKernel\Kernel;

/**
 * @see https://tech.quartetcom.co.jp/2016/12/19/functional-testing-syfony-bundle/
 */
class WebTestCase extends BaseWebTestCase
{
    protected static function getKernelClass(): string
    {
        return Kernel::VERSION_ID >= 50100 ? TestKernel::class : TestKernelForBC::class;
    }
}
