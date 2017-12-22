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

namespace WvisionBundle\Application;

final class Version
{
    const MAJOR_VERSION = '2';
    const MINOR_VERSION = '0';
    const RELEASE_VERSION = '0';
    const EXTRA_VERSION = null;

    /**
     * @return string
     */
    public static function getVersion()
    {
        $version = sprintf('%s.%s.%s', self::MAJOR_VERSION, self::MINOR_VERSION, self::RELEASE_VERSION);

        if (self::EXTRA_VERSION) {
            $version = sprintf('%s-%s', $version, self::EXTRA_VERSION);
        }

        return $version;
    }
}