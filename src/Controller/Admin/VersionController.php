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

namespace WvisionBundle\Controller\Admin;

use Pimcore\Bundle\AdminBundle\Controller\AdminController;
use Pimcore\Bundle\AdminBundle\HttpFoundation\JsonResponse;
use WvisionBundle\Application\Version;

class VersionController extends AdminController
{
    /**
     * Returns the current version and build number of the bundle.
     *
     * @return JsonResponse
     */
    public function getVersionAction()
    {
        $version = Version::getVersion();
        $build = Version::getBuild();

        return $this->json([
            'version' => $version,
            'build' => $build
        ]);
    }
}