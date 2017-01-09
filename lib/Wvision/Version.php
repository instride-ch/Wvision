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

use Wvision\Model\Configuration;

/**
 * Class Version
 * @package Wvision
 */
class Version
{
    /**
     * Get Wvision Plugin Config.
     *
     * @return array
     */
    protected static function getPluginConfig()
    {
        return Configuration::getPluginConfig()->plugin;
    }

    /**
     * Get Wvision Version.
     *
     * @return string
     */
    public static function getVersion()
    {
        return self::getPluginConfig()->pluginVersion;
    }

    /**
     * Get Wvision Build.
     *
     * @return int
     */
    public static function getBuild()
    {
        return self::getPluginConfig()->pluginRevision;
    }
}
