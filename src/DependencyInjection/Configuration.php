<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('ttskch_paginator');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('page')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('name')->defaultValue('page')->end()
                        ->integerNode('range')->defaultValue(5)->end()
                    ->end()
                ->end()
                ->arrayNode('limit')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('name')->defaultValue('limit')->end()
                        ->integerNode('default')->defaultValue(10)->end()
                    ->end()
                ->end()
                ->arrayNode('sort')->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('key')->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('name')->defaultValue('sort')->end()
                            ->end()
                        ->end()
                        ->arrayNode('direction')->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('name')->defaultValue('direction')->end()
                                ->scalarNode('default')
                                    ->info('"asc" or "desc"')
                                    ->validate()->ifNotInArray(['asc', 'desc'])->thenInvalid('Invalid direction value. Only "asc" or "desc" is allowed.')->end()
                                    ->defaultValue('asc')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('template')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('pager')->defaultValue('@TtskchPaginator/pager/default.html.twig')->end()
                        ->scalarNode('sortable')->defaultValue('@TtskchPaginator/sortable/default.html.twig')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
