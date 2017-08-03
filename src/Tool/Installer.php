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

use Pimcore\Config;
use Pimcore\Model\Tool\Setup;
use Pimcore\Model\User;
use Pimcore\Tool;
use Psr\Log\LoggerInterface;
use Pimcore\Model\Translation;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\Serializer;
use WvisionBundle\Configuration\Configuration;

class Installer
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var string
     */
    private $installSourcesPath;

    /**
     * @var Filesystem
     */
    private $fileSystem;

    /**
     * Install constructor.
     *
     * @param LoggerInterface $logger
     * @param Serializer $serializer
     */
    public function __construct(LoggerInterface $logger, Serializer $serializer)
    {
        $this->logger = $logger;
        $this->serializer = $serializer;

        $this->installSourcesPath = __DIR__ . '/../Resources/install';
        $this->fileSystem = new Filesystem();
    }

    /**
     * {@inheritdoc}
     */
    public function install()
    {
        $this->copyConfigFiles();

        $this->replaceSystemSettings();
        $this->installWorkflow();
        $this->copyStaticFiles();
        $this->copyUserImage();
        $this->copyRobotsTxt();

        // TODO: Implement Resource Installer

//        $this->installTranslations();
//        $this->injectDbData();

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
        return $this->fileSystem->exists(Configuration::SYSTEM_CONFIG_FILE_PATH);
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

    /**
     * Copies a sample config file, if it does not already exist.
     */
    private function copyConfigFiles()
    {
        if (!$this->fileSystem->exists(Configuration::SYSTEM_CONFIG_FILE_PATH)) {
            $this->fileSystem->copy(
                $this->installSourcesPath . '/config.yml',
                Configuration::SYSTEM_CONFIG_FILE_PATH
            );
        }
    }

    /**
     * Replaces the current pimcore system settings.
     */
    private function replaceSystemSettings()
    {
        $this->fileSystem->copy(
            $this->installSourcesPath . '/system.php',
            PIMCORE_CONFIGURATION_DIRECTORY
        );
    }

    /**
     * Installs the workflow.
     */
    private function installWorkflow()
    {
        // TODO: Call it installRootLevelFiles instead?
        $this->fileSystem->mirror(
            $this->installSourcesPath . '/workflow',
            PIMCORE_PROJECT_ROOT
        );
    }

    /**
     * Copies all static files for development into the project's root,
     * if it does not already exist.
     */
    private function copyStaticFiles()
    {
        if (!$this->fileSystem->exists(PIMCORE_PROJECT_ROOT . '/assets')) {
            $this->fileSystem->rename(
                $this->installSourcesPath . '/static',
                PIMCORE_PROJECT_ROOT . '/assets'
            );
        }
    }

    /**
     * Copies a user image recursively to the user image directory.
     */
    private function copyUserImage()
    {
        $this->fileSystem->copy(
            $this->installSourcesPath . '/user-2.png',
            PIMCORE_USERIMAGE_DIRECTORY
        );
    }

    /**
     * Copies a robots.txt recursively to the pimcore project root.
     */
    private function copyRobotsTxt()
    {
        $this->fileSystem->copy(
            $this->installSourcesPath . '/robots.txt',
            PIMCORE_PROJECT_ROOT
        );
    }

    /**
     * TODO: Decide whether this is needed?
     */
    public function installTranslations()
    {
        $csv = $this->installSourcesPath . '/translations/data.csv';
        $csvAdmin = $this->installSourcesPath . '/translations/admin/data.csv';

        Translation\Website::importTranslationsFromFile($csv, true, Tool\Admin::getLanguages());
        Translation\Admin::importTranslationsFromFile($csvAdmin, true, Tool\Admin::getLanguages());
    }

    /**
     * TODO: Decide whether this is needed?
     */
    public function injectDbData()
    {
        $setup = new Setup();
        $setup->insertDump($this->installSourcesPath . '/sql/install.sql');
    }

    /**
     * Creates a new w-vision admin user.
     *
     * @param $username
     * @param $password
     */
    public function createUser($username, $password)
    {
        $settings = [
          'username' => $username,
          'password' => $password,
        ];

        if ($user = User::getByName($settings['username'])) {
            $user->delete();
        }

        $user = User::create([
           'parentId' => 0,
           'username' => $settings['username'],
           'password' => Tool\Authentication::getPasswordHash($settings['username'], $settings['password']),
           'active' => true
        ]);
        $user->setAdmin(true);
        $user->save();
    }
}