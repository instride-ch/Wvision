<?php
/**
 * w-vision
 *
 * LICENSE
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that is distributed with this source code.
 *
 * @copyright  Copyright (c) 2017 Woche-Pass AG (https://www.w-vision.ch)
 */

namespace WvisionBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('wvision');

        $rootNode
            ->children()
                ->arrayNode('newsletter')
                    ->children()
                    ->arrayNode('default')->isRequired()
                        ->children()
                            ->scalarNode('folder')->isRequired()->cannotBeEmpty()->end()
                            ->arrayNode('smtp')->isRequired()->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('host')->isRequired()->cannotBeEmpty()->end()
                                    ->scalarNode('security')->defaultValue(null)->end()
                                    ->integerNode('port')->defaultValue(25)->end()
                                    ->scalarNode('name')->isRequired()->cannotBeEmpty()->end()
                                    ->scalarNode('auth_method')->isRequired()->cannotBeEmpty()->end()
                                    ->scalarNode('user')->isRequired()->cannotBeEmpty()->end()
                                    ->scalarNode('password')->isRequired()->cannotBeEmpty()->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode('sites')
                        ->useAttributeAsKey('main_domain')
                        ->prototype('array')
                            ->children()
                                ->scalarNode('main_domain')->cannotBeEmpty()->end()
                                ->scalarNode('folder')->end()
                                ->arrayNode('smtp')->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('host')->isRequired()->cannotBeEmpty()->end()
                                        ->scalarNode('security')->defaultValue(null)->end()
                                        ->integerNode('port')->defaultValue(25)->end()
                                        ->scalarNode('name')->isRequired()->cannotBeEmpty()->end()
                                        ->scalarNode('auth_method')->isRequired()->cannotBeEmpty()->end()
                                        ->scalarNode('user')->isRequired()->cannotBeEmpty()->end()
                                        ->scalarNode('password')->isRequired()->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}