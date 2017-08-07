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

namespace WvisionBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterInstallersPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('wvision.registry.installers')) {
            return;
        }

        $registry = $container->getDefinition('wvision.registry.installers');

        foreach ($container->findTaggedServiceIds('wvision.installer') as $id => $attributes) {
            if (!isset($attributes[0]['type'], $attributes[0]['priority'])) {
                throw new \InvalidArgumentException('Tagged Service `' . $id . '` needs to have `type` and `priority` attributes.');
            }

            $registry->addMethodCall('register', [$attributes[0]['type'], $attributes[0]['priority'], new Reference($id)]);
        }
    }
}