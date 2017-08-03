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

final class AssetConfiguration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('assets');

        $rootNode
            ->children()
                ->arrayNode('assets')
                    ->arrayPrototype()
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('filename')->cannotBeEmpty()->end()
                            ->scalarNode('type')->cannotBeEmpty()->end()
                            ->scalarNode('path')->cannotBeEmpty()->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}