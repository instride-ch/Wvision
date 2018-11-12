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

namespace WvisionBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;
use WvisionBundle\Installer\BundleInstaller;

class WvisionBundle extends AbstractPimcoreBundle
{
    /**
     * {@inheritdoc}
     */
    public function getInstaller()
    {
        return $this->container->get(BundleInstaller::class);
    }
}
