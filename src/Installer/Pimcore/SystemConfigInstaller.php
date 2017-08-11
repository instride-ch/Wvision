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

namespace WvisionBundle\Installer\Pimcore;

use Pimcore\Config;
use Pimcore\File;
use Pimcore\Tool;
use Symfony\Component\Console\Output\OutputInterface;
use WvisionBundle\Installer\ResourceInstallerInterface;

final class SystemConfigInstaller implements ResourceInstallerInterface
{
    /**
     * {@inheritdoc}
     */
    public function installResources(OutputInterface $output)
    {
        $config = Config::getSystemConfig()->toArray();

        $config = array_merge_recursive([
            'general' => [
                'timezone' => 'Europe/Zurich',
                'validLanguages' => 'de_CH',
                'fallbackLanguages' => [
                    'de_CH' => 'de'
                ],
                'defaultLanguage' => 'de_CH',
                'loginscreencustomimage' => '//www.w-vision.ch/static/img/backend/admin-bg.jpg'
            ],
            'database' => [
                'params' => [
                    'username' => 'xxx',
                    'password' => 'xxx',
                    'dbname' => 'xxx'
                ]
            ],
            'services' => [
                'google' => [
                    'browserapikey' => 'AIzaSyAiOfc83woVr_Xrd8yXh59BBSEyIKWxwD4'
                ]
            ],
            'email' => [
                'sender' => [
                    'name' => Tool::getHostname(),
                    'email' => 'support@w-vision.ch'
                ],
                'debug' => [
                    'emailaddresses' => 'support@w-vision.ch'
                ]
            ]
        ], $config);

        $configFile = Config::locateConfigFile('system.php');
        File::putPhpFile($configFile, to_php_data_file_format($config));
    }
}