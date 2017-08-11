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

namespace WvisionBundle\Configuration;

use Pimcore\Extension\Bundle\PimcoreBundleManager;
use Symfony\Component\EventDispatcher\GenericEvent;

class Configuration
{
    const SYSTEM_CONFIG_FILE_PATH = PIMCORE_PRIVATE_VAR . '/bundles/WvisionBundle/config.yml';

    /**
     * @var array
     */
    protected $config;

    /**
     * @var array
     */
    protected $systemConfig;

    /**
     * @var PimcoreBundleManager
     */
    protected $bundleManager;

    /**
     * @param PimcoreBundleManager $bundleManager
     */
    public function __construct(PimcoreBundleManager $bundleManager)
    {
        $this->bundleManager = $bundleManager;
    }

    /**
     * @param array $config
     */
    public function setConfig($config = [])
    {
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function getConfigNode()
    {
        return $this->config;
    }

    /**
     * @return mixed
     */
    public function getConfigArray()
    {
        return $this->config;
    }

    /**
     * @param $slot
     * @return mixed
     */
    public function getConfig($slot)
    {
        return $this->config[$slot];
    }

    /**
     * @param $slot
     * @param null $locale
     * @return mixed
     */
    public function getLocalizedPath($slot, $locale = null)
    {
        $data = $this->getConfig($slot);

        $event = new GenericEvent($this, [
            'route' => $data
        ]);

        \Pimcore::getEventDispatcher()->dispatch('wvision.path.route', $event);

        if ($event->hasArgument('url')) {
            $url = $event->getArgument('url');
        } else {
            $lang = '';
            if (!empty($locale)) {
                $lang = (string) $locale;
            }

            $url = str_replace('/%lang', '/' . $lang, $data);
        }

        return $url;
    }

    /**
     * @param array $config
     */
    public function setSystemConfig($config = [])
    {
        $this->systemConfig = $config;
    }

    /**
     * @param null $slot
     * @return mixed
     */
    public function getSystemConfig($slot = null)
    {
        return $this->systemConfig[$slot];
    }

    /**
     * @param string $bundleName
     * @return bool
     */
    public function hasBundle($bundleName = 'ExtensionBundle\ExtensionBundle')
    {
        try {
            $hasExtension = $this->bundleManager->isEnabled($bundleName);
        } catch (\Exception $e) {
            $hasExtension = false;
        }

        return $hasExtension;
    }
}