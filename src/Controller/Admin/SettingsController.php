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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use WvisionBundle\Application\Version;
use WvisionBundle\Configuration\Configuration;

class SettingsController extends AdminController
{
    /**
     * Returns the current version and build number of the bundle.
     *
     * @return JsonResponse
     */
    public function getSettingsAction()
    {
        $version = Version::getVersion();
        $build = Version::getBuild();

        return $this->json([
            'version' => $version,
            'build' => $build
        ]);
    }

    /**
     * Returns an array with all bundle specific settings.
     *
     * @return JsonResponse
     */
    public function getAction()
    {
        $config = new Configuration\Listing();

        $valueArray = [];
        foreach ($config->getConfigurations() as $c) {
            $valueArray[$c->getKey()] = $c->getData();
        }

        return $this->json(['settings' => $valueArray]);
    }

    /**
     * Sets
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function setAction(Request $request)
    {
        $serializer = new Serializer();
        $values = $serializer->decode($request->get('settings'), 'json');
        $values = array_htmlspecialchars($values);

        foreach ($values as $key => $value) {
            Configuration::set($key, $value);
        }

        return $this->json(['success' => true]);
    }
}