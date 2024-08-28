<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Tests\Unit\DependencyInjection;

use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Ttskch\PaginatorBundle\Config;
use Ttskch\PaginatorBundle\DependencyInjection\TtskchPaginatorExtension;
use Ttskch\PaginatorBundle\Paginator;
use Ttskch\PaginatorBundle\Tests\Unit\TestCase;
use Ttskch\PaginatorBundle\Twig\TtskchPaginatorExtension as TwigExtension;
use Ttskch\PaginatorBundle\Twig\TtskchPaginatorRuntime;

class TtskchPaginatorExtensionTest extends TestCase
{
    use ProphecyTrait;

    public function testLoad(): void
    {
        $SUT = new TtskchPaginatorExtension();

        $configs = [
            [
                'page' => [
                    'name' => 'page',
                    'range' => 5,
                ],
                'limit' => [
                    'name' => 'limit',
                    'default' => 10,
                ],
                'sort' => [
                    'key' => [
                        'name' => 'sort',
                    ],
                    'direction' => [
                        'name' => 'direction',
                        'default' => 'asc',
                    ],
                ],
                'template' => [
                    'pager' => '@TtskchPaginator/pager/default.html.twig',
                    'sortable' => '@TtskchPaginator/sortable/default.html.twig',
                ],
            ],
        ];

        $container = $this->prophesize(ContainerBuilder::class);
        $container->setParameter('ttskch_paginator.page.name', 'page')->shouldBeCalled();
        $container->setParameter('ttskch_paginator.page.range', 5)->shouldBeCalled();
        $container->setParameter('ttskch_paginator.limit.name', 'limit')->shouldBeCalled();
        $container->setParameter('ttskch_paginator.limit.default', 10)->shouldBeCalled();
        $container->setParameter('ttskch_paginator.sort.key.name', 'sort')->shouldBeCalled();
        $container->setParameter('ttskch_paginator.sort.direction.name', 'direction')->shouldBeCalled();
        $container->setParameter('ttskch_paginator.sort.direction.default', 'asc')->shouldBeCalled();
        $container->setParameter('ttskch_paginator.template.pager', '@TtskchPaginator/pager/default.html.twig')->shouldBeCalled();
        $container->setParameter('ttskch_paginator.template.sortable', '@TtskchPaginator/sortable/default.html.twig')->shouldBeCalled();
        $container->fileExists(Argument::that(fn (string $path) => str_ends_with($path, '/../Resources/config/services.yaml')))->willReturn(true);
        foreach ([
            Config::class,
            Paginator::class,
            TwigExtension::class,
            TtskchPaginatorRuntime::class,
        ] as $id) {
            $container->setDefinition($id, Argument::type(Definition::class))->willReturn($this->prophesize(Definition::class)->reveal());
            $container->removeBindings($id)->shouldBeCalled();
        }

        $SUT->load($configs, $container->reveal());
    }
}
