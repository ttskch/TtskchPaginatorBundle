<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Twig;

use Ttskch\PaginatorBundle\Context;
use Ttskch\PaginatorBundle\WebTestCase;
use Twig\Environment;

class TtskchPaginatorExtensionTest extends WebTestCase
{
    /**
     * @var TtskchPaginatorExtension
     */
    private $SUT;

    public function setUp(): void
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();

        $data = [
            ['id' => 1, 'name' => 'name1'],
            ['id' => 2, 'name' => 'name2'],
            ['id' => 3, 'name' => 'name3'],
            ['id' => 4, 'name' => 'name4'],
            ['id' => 5, 'name' => 'name5'],
        ];

        /** @var Context $context */
        $context = $container->get('ttskch_paginator.context');
        $context->initialize(
            '',
            function () {},
            function () use ($data) {
                return count($data);
            }
        );

        /** @var Environment $twig */
        $twig = $container->get('twig');

        $this->SUT = new TtskchPaginatorExtension($context, $twig);
    }

    public function testRenderPager(): void
    {
        $text = $this->SUT->renderPager();

        $expected = <<<EOT


limit
2
page
1
1
2
1
3
1
2
1
5

EOT;

        $this->assertEquals($expected, $text);
    }

    public function testRenderSortableLink(): void
    {
        $text = $this->SUT->renderSortableLink('name', 'Name');

        $expected = <<<EOT

1
sort
name
direction

asc
Name

EOT;
        $this->assertEquals($expected, $text);
    }
}
