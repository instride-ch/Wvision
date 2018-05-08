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

class WorkflowInstaller implements ResourceInstallerInterface
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
        $sourceDir = $this->installSourcesPath . '/workflow';
        $allFiles = scandir($sourceDir, SCANDIR_SORT_ASCENDING);
        $files = array_diff($allFiles, ['.', '..']);

        foreach ($files as $file) {
            $target = PIMCORE_PROJECT_ROOT . '/' . $file;
            $source = $sourceDir . '/' . $file;

            if (file_exists($source) && !file_exists($target)) {
                $this->fileSystem->copy($source, $target);
            }
        }
    }
}