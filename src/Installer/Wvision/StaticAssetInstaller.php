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

namespace WvisionBundle\Installer\Wvision;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use WvisionBundle\Installer\ResourceInstallerInterface;

class StaticAssetInstaller implements ResourceInstallerInterface
{
    /**
     * @var Filesystem
     */
    private $fileSystem;

    /**
     * @var string
     */
    private $installSourcesPath;

    /**
     * @param Filesystem $fileSystem
     */
    public function __construct(Filesystem $fileSystem)
    {
        $this->fileSystem = $fileSystem;

        $this->installSourcesPath = __DIR__ . '/../../Resources/install';
    }

    /**
     * {@inheritdoc}
     */
    public function installResources(OutputInterface $output)
    {
        if (!$this->fileSystem->exists(PIMCORE_PROJECT_ROOT . '/assets')) {
            $this->fileSystem->mirror(
                $this->installSourcesPath . '/assets',
                PIMCORE_PROJECT_ROOT
            );
        }
    }
}