#!/usr/bin/env php
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

ob_get_clean();

define('PIMCORE_CONSOLE', true);

require_once dirname(__FILE__) . '/../../../pimcore/cli/startup.php';

$conf = \Pimcore\Config::getSystemConfig();
if(!$conf) {
   throw new \Exception("Please run install-pimcore.php first");
}

\Pimcore\ExtensionManager::enable('plugin', 'Wvision');

$config = \Pimcore\ExtensionManager::getPluginConfig($id);
$className = $config["plugin"]["pluginClassName"];

$message = $className::install();

if (!$className::isInstalled()) {
    throw new \Exception(sprintf("Installation error (%s)", $message));
}