<?php
/**
 * w-vision
 *
 * LICENSE
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that is distributed with this source code.
 *
 * @copyright  Copyright (c) 2017 Woche-Pass AG (http://www.w-vision.ch)
 */

namespace WvisionBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;

class WvisionBundle extends AbstractPimcoreBundle
{
    /**
     * {@inheritdoc}
     */
    public function getInstaller()
    {
    }

    /**
     * @return string[]
     */
    public function getJsPaths()
    {
        return [
            '/bundles/wvision/pimcore/js/settings.js'
        ];
    }

    /**
     * @return string[]
     */
    public function getCssPaths()
    {
        return [];
    }
}
