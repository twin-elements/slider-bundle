<?php

namespace TwinElements\SliderBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('twin_elements_slider');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('image_size')
                    ->children()
                        ->integerNode('width')->end()
                        ->integerNode('height')->end()
                    ->end()
                ->end()
                ->arrayNode('mobile_image_size')
                    ->children()
                        ->integerNode('width')->end()
                        ->integerNode('height')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
