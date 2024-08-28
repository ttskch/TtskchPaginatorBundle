<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Tests\Unit\Counter;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Ttskch\PaginatorBundle\Counter\ArrayCounter;
use Ttskch\PaginatorBundle\Criteria\CriteriaInterface;

class ArrayCounterTest extends TestCase
{
    use ProphecyTrait;

    public function testCount(): void
    {
        $array = [1, 2, 3];

        $SUT = new ArrayCounter($array);

        $criteria = $this->prophesize(CriteriaInterface::class);

        self::assertSame(3, $SUT->count($criteria->reveal()));
    }
}
