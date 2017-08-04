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

namespace WvisionBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;

class WvisionBundle extends AbstractPimcoreBundle
{
    /**
     * {@inheritdoc}
     */
    public function getInstaller()
    {
        return $this->container->get('WvisionBundle\Tool\Installer');
    }

    /**
     * @return string[]
     */
    public function getJsPaths()
    {
        return [
            '/bundles/wvision/pimcore/js/global.js',

            '/bundles/wvision/pimcore/js/wvision/settings.js',
            '/bundles/wvision/pimcore/js/wvision/helpers.js',

            '/bundles/wvision/pimcore/js/wvision/newsletter/sendingPanel.js'
        ];
    }

    /**
     * @return string[]
     */
    public function getCssPaths()
    {
        return [
            '/bundles/wvision/pimcore/css/bundle.css'
        ];
    }
}
