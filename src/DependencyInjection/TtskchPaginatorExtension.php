<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Ttskch\PaginatorBundle\Criteria\CriteriaInterface;

class TtskchPaginatorExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $processor = new Processor();

        $config = $processor->processConfiguration($configuration, $configs);

        $container->setParameter('ttskch_paginator.page.name', strval($config['page']['name']));
        $container->setParameter('ttskch_paginator.page.range', intval($config['page']['range']));
        $container->setParameter('ttskch_paginator.limit.name', strval($config['limit']['name']));
        $container->setParameter('ttskch_paginator.limit.default', intval($config['limit']['default']));
        $container->setParameter('ttskch_paginator.sort.key.name', strval($config['sort']['key']['name']));
        $container->setParameter('ttskch_paginator.sort.direction.name', strval($config['sort']['direction']['name']));
        $container->setParameter('ttskch_paginator.sort.direction.default', CriteriaInterface::ASC === $config['sort']['direction']['default'] ? CriteriaInterface::ASC : CriteriaInterface::DESC);
        $container->setParameter('ttskch_paginator.template.pager', strval($config['template']['pager']));
        $container->setParameter('ttskch_paginator.template.sortable', strval($config['template']['sortable']));

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');
    }
}
