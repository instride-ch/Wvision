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

namespace WvisionBundle\Installer;

use Symfony\Component\Console\Output\OutputInterface;
use CoreShop\Component\Registry\PrioritizedServiceRegistryInterface;

class CompositeResourceInstaller implements ResourceInstallerInterface
{
    /**
     * @var PrioritizedServiceRegistryInterface
     */
    protected $serviceRegistry;

    /**
     * @param PrioritizedServiceRegistryInterface $serviceRegistry
     */
    public function __construct(PrioritizedServiceRegistryInterface $serviceRegistry)
    {
        $this->serviceRegistry = $serviceRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function installResources(OutputInterface $output)
    {
        foreach ($this->serviceRegistry->all() as $installer) {
            if ($installer instanceof ResourceInstallerInterface) {
                $installer->installResources($output);
            }
        }
    }
}