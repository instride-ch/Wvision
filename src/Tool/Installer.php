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

namespace WvisionBundle\Tool;

use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Filesystem\Filesystem;
use WvisionBundle\Configuration\Configuration;
use WvisionBundle\Installer\ResourceInstallerInterface;

class Installer
{
    /**
     * @var Filesystem
     */
    private $fileSystem;

    /**
     * @var ResourceInstallerInterface
     */
    private $installer;

    /**
     * @param Filesystem $fileSystem
     * @param ResourceInstallerInterface $installer
     */
    public function __construct(Filesystem $fileSystem, ResourceInstallerInterface $installer)
    {
        $this->fileSystem = $fileSystem;
        $this->installer = $installer;
    }

    /**
     * {@inheritdoc}
     */
    public function install()
    {
        $this->installer->installResources(new NullOutput());

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function uninstall()
    {
        if ($this->fileSystem->exists(Configuration::SYSTEM_CONFIG_FILE_PATH)) {
            $this->fileSystem->rename(
                Configuration::SYSTEM_CONFIG_FILE_PATH,
                PIMCORE_PRIVATE_VAR . '/bundles/WvisionBundle/config_backup.yml'
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isInstalled()
    {
        return true; //$this->fileSystem->exists(Configuration::SYSTEM_CONFIG_FILE_PATH);
    }

    /**
     * {@inheritdoc}
     */
    public function canBeInstalled()
    {
        return !$this->fileSystem->exists(Configuration::SYSTEM_CONFIG_FILE_PATH);
    }

    /**
     * {@inheritdoc}
     */
    public function canBeUninstalled()
    {
        return $this->fileSystem->exists(Configuration::SYSTEM_CONFIG_FILE_PATH);
    }

    /**
     * {@inheritdoc}
     */
    public function needsReloadAfterInstall()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function canBeUpdated()
    {
        return false;
    }
}