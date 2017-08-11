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

final class PredefinedPropertyConfiguration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('predefined-properties');

        $rootNode
            ->children()
                ->arrayNode('properties')
                ->useAttributeAsKey('key')
                    ->arrayPrototype()
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('name')->cannotBeEmpty()->end()
                            ->scalarNode('description')->end()
                            ->scalarNode('key')->cannotBeEmpty()->end()
                            ->scalarNode('type')->cannotBeEmpty()->defaultValue('text')->end()
                            ->scalarNode('data')->end()
                            ->scalarNode('config')->end()
                            ->scalarNode('ctype')->cannotBeEmpty()->defaultValue('document')->end()
                            ->booleanNode('inheritable')->defaultValue(false)->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}