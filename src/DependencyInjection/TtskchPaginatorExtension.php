<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class TtskchPaginatorExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $processor = new Processor();

        $config = $processor->processConfiguration($configuration, $configs);

        $container->setParameter('ttskch_paginator.page.name', $config['page']['name']);
        $container->setParameter('ttskch_paginator.page.range', $config['page']['range']);
        $container->setParameter('ttskch_paginator.limit.name', $config['limit']['name']);
        $container->setParameter('ttskch_paginator.limit.default', $config['limit']['default']);
        $container->setParameter('ttskch_paginator.sort.key.name', $config['sort']['key']['name']);
        $container->setParameter('ttskch_paginator.sort.direction.name', $config['sort']['direction']['name']);
        $container->setParameter('ttskch_paginator.sort.direction.default', $config['sort']['direction']['default']);
        $container->setParameter('ttskch_paginator.template.pager', $config['template']['pager']);
        $container->setParameter('ttskch_paginator.template.sortable', $config['template']['sortable']);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');
    }
}
