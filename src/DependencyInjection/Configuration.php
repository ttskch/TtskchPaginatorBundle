<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Ttskch\PaginatorBundle\Criteria\CriteriaInterface;

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
                                    ->validate()->ifNotInArray([CriteriaInterface::ASC, CriteriaInterface::DESC])->thenInvalid(sprintf('Invalid direction value. Only "%s" or "%s" is allowed.', CriteriaInterface::ASC, CriteriaInterface::DESC))->end()
                                    ->defaultValue(CriteriaInterface::ASC)->end()
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
