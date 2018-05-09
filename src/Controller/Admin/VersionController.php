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

namespace WvisionBundle\Controller\Admin;

use Pimcore\Bundle\AdminBundle\Controller\AdminController;
use Symfony\Component\HttpFoundation\JsonResponse;
use WvisionBundle\Application\Version;

class VersionController extends AdminController
{
    /**
     * Returns the current version of the bundle
     *
     * @return JsonResponse
     */
    public function getVersionAction(): JsonResponse
    {
        $version = Version::getVersion();

        return $this->json(['version' => $version]);
    }
}