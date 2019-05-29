<?php
/**
 * w-vision
 *
 * LICENSE
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that is distributed with this source code.
 *
 * @copyright  Copyright (c) 2018 w-vision AG (https://www.w-vision.ch)
 */

namespace WvisionBundle\Installer;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

final class AssetsInstaller
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var Filesystem
     */
    private $fileSystem;

    /**
     * @param KernelInterface $kernel
     * @param Filesystem $fileSystem
     */
    public function __construct(KernelInterface $kernel, Filesystem $fileSystem)
    {
        $this->kernel = $kernel;
        $this->fileSystem = $fileSystem;
    }

    /**
     * {@inheritdoc}
     */
    public function installAssets(): void
    {
        $installSourcesPath = $this->kernel->locateResource('@WvisionBundle/Resources/install');

        if (!$this->fileSystem->exists(PIMCORE_PROJECT_ROOT . '/web/var/assets/demo')) {
            $this->fileSystem->mirror(
                $installSourcesPath . '/web/var/assets',
                PIMCORE_PROJECT_ROOT . '/web/var/assets/demo'
            );
        }
    }
}
