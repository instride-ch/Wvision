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

use Doctrine\DBAL\Migrations\Version;
use Doctrine\DBAL\Schema\Schema;
use Pimcore\Extension\Bundle\Installer\MigrationInstaller;

final class BundleInstaller extends MigrationInstaller
{
    /**
     * @var AssetsInstaller
     */
    private $assetsInstaller;

    /**
     * @var DemoInstaller
     */
    private $demoInstaller;

    /**
     * @param AssetsInstaller $assetsInstaller
     */
    public function setAssetsInstaller(AssetsInstaller $assetsInstaller)
    {
        $this->assetsInstaller = $assetsInstaller;
    }

    /**
     * @param DemoInstaller $demoInstaller
     */
    public function setDemoInstaller(DemoInstaller $demoInstaller)
    {
        $this->demoInstaller = $demoInstaller;
    }

    /**
     * Install Migrations
     */
    protected function beforeInstallMigration()
    {
        $this->assetsInstaller->installAssets();
        $this->demoInstaller->installDemo();
    }

    /**
     * @param Schema  $schema
     * @param Version $version
     */
    public function migrateInstall(Schema $schema, Version $version)
    {
    }

    /**
     * @param Schema  $schema
     * @param Version $version
     */
    public function migrateUninstall(Schema $schema, Version $version)
    {
    }
}