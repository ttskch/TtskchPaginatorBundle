<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Tests\Unit\Slicer;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Ttskch\PaginatorBundle\Criteria\CriteriaInterface;
use Ttskch\PaginatorBundle\Slicer\ArraySlicer;

class ArraySlicerTest extends TestCase
{
    use ProphecyTrait;

    public function testSlice(): void
    {
        $array = [
            ['id' => 1, 'name' => 'foo'],
            ['id' => 2, 'name' => 'bar'],
            ['id' => 3, 'name' => 'baz'],
        ];

        $SUT = new ArraySlicer($array);

        $criteria = $this->prophesize(CriteriaInterface::class);
        $criteria->getSort()->willReturn('id');
        $criteria->getDirection()->willReturn(CriteriaInterface::DESC);
        $criteria->getLimit()->willReturn(2);
        $criteria->getPage()->willReturn(2);

        self::assertSame([
            ['id' => 1, 'name' => 'foo'],
        ], $SUT->slice($criteria->reveal()));
    }
}
