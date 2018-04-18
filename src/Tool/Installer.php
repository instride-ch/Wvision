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

use Doctrine\DBAL\Migrations\Version;
use Doctrine\DBAL\Schema\Schema;
use Pimcore\Extension\Bundle\Installer\MigrationInstaller;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Filesystem\Filesystem;
use WvisionBundle\Installer\ResourceInstallerInterface;

final class Installer extends MigrationInstaller
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
     */
    public function setFileSystem(Filesystem $fileSystem)
    {
        $this->fileSystem = $fileSystem;
    }

    /**
     * @param ResourceInstallerInterface $installer
     */
    public function setInstaller(ResourceInstallerInterface $installer)
    {
        $this->installer = $installer;
    }

    /**
     * Install Migrations
     */
    protected function beforeInstallMigration()
    {
        $this->installer->installResources(new NullOutput());
    }

    /**
     * @param Schema $schema
     * @param Version $version
     */
    public function migrateInstall(Schema $schema, Version $version)
    {

    }

    /**
     * @param Schema $schema
     * @param Version $version
     */
    public function migrateUninstall(Schema $schema, Version $version)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function needsReloadAfterInstall()
    {
        return false;
    }
}