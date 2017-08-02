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

namespace WvisionBundle\Tool\Installer\Configuration;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class DocumentConfiguration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('documents');

        $rootNode
            ->children()
                ->arrayNode('documents')
                    ->arrayPrototype()
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('key')->cannotBeEmpty()->end()
                            ->scalarNode('type')->cannotBeEmpty()->end()
                            ->scalarNode('path')->cannotBeEmpty()->end()
                            ->scalarNode('module')->end()
                            ->scalarNode('controller')->end()
                            ->scalarNode('action')->end()
                            ->arrayNode('content')
                                ->useAttributeAsKey('language')
                                ->arrayPrototype()
                                    ->arrayPrototype()
                                        ->children()
                                            ->scalarNode('type')->isRequired()->end()
                                            ->scalarNode('value')->isRequired()->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}