<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Tests\Unit;

use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Ttskch\PaginatorBundle\DependencyInjection\TtskchPaginatorExtension;

class TestCase extends BaseTestCase
{
    public function createXmlBundleTestContainer(): ContainerBuilder
    {
        $container = new ContainerBuilder(new ParameterBag([
            'kernel.debug' => false,
            'kernel.bundles' => ['XmlBundle' => 'Fixtures\Bundles\XmlBundle\XmlBundle'],
            'kernel.cache_dir' => sys_get_temp_dir(),
            'kernel.environment' => 'test',
            'kernel.root_dir' => __DIR__.'/../../../../', // src dir
            'kernel.project_dir' => __DIR__.'/../../../../', // src dir
            'kernel.bundles_metadata' => [],
            'container.build_id' => uniqid(),
        ]));

        if (class_exists(AnnotationReader::class)) {
            $container->set('annotation_reader', new AnnotationReader());
        }

        $extension = new TtskchPaginatorExtension();
        $container->registerExtension($extension);
        $extension->load([
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
        ], $container);

        return $container;
    }
}
