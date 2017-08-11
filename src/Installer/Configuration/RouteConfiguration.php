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

namespace WvisionBundle\Installer\Configuration;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class RouteConfiguration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('staticroutes');

        $rootNode
            ->children()
                ->arrayNode('routes')
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('name')->cannotBeEmpty()->end()
                            ->scalarNode('pattern')->cannotBeEmpty()->end()
                            ->scalarNode('reverse')->cannotBeEmpty()->end()
                            ->scalarNode('module')->cannotBeEmpty()->end()
                            ->scalarNode('controller')->cannotBeEmpty()->end()
                            ->scalarNode('action')->cannotBeEmpty()->end()
                            ->scalarNode('variables')->defaultValue('')->end()
                            ->integerNode('priority')->defaultValue(1)->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}