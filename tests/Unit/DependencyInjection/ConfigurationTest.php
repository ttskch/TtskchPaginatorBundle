<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Tests\Unit\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Ttskch\PaginatorBundle\DependencyInjection\Configuration;

class ConfigurationTest extends TestCase
{
    use ProphecyTrait;

    public function testGetConfigTreeBuilder(): void
    {
        $SUT = new Configuration();

        $treeBuilder = $SUT->getConfigTreeBuilder();
        $tree = $treeBuilder->buildTree();

        $config = [
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
        ];

        self::assertSame($config, $tree->normalize($config));
    }
}
