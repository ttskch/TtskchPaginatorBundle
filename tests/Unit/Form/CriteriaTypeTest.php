<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Tests\Unit\Form;

use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\Form\Test\TypeTestCase;
use Ttskch\PaginatorBundle\Criteria\Criteria;
use Ttskch\PaginatorBundle\Form\CriteriaType;

class CriteriaTypeTest extends TypeTestCase
{
    use ProphecyTrait;

    public function testSubmitValidData(): void
    {
        $criteria = new Criteria('id');
        $criteria->setPage(1);
        $criteria->setLimit(1);
        $criteria->setDirection('asc');

        $formData = [
            'page' => '10',
            'limit' => '10',
            'sort' => 'createdAt',
            'direction' => 'desc',
        ];

        $form = $this->factory->create(CriteriaType::class, $criteria);

        $form->submit($formData);

        self::assertTrue($form->isSynchronized());

        self::assertSame(10, $criteria->getPage());
        self::assertSame(10, $criteria->getLimit());
        self::assertSame('createdAt', $criteria->getSort());
        self::assertSame('desc', $criteria->getDirection());
    }
}
