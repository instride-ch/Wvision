<?php
/**
 * w-vision
 *
 * LICENSE
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that is distributed with this source code.
 *
 * @copyright  Copyright (c) 2016 Woche-Pass AG (http://www.w-vision.ch)
 */

namespace Wvision;

use Composer\Script\Event;
use Composer\Util\Filesystem;
use Composer\Installer\PackageEvent;
use Pimcore\ExtensionManager;

class Composer
{
    public static function postInstall(Event $event)
    {
        ExtensionManager::enable('plugin', 'Wvision');

        $config = ExtensionManager::getPluginConfig('Wvision');
        $className = $config["plugin"]["pluginClassName"];

        $message = $className::install();

        if(!$className::isInstalled()) {
            throw new \Exception(sprintf("Installation failed (%s)", $message));
        }
    }
}
