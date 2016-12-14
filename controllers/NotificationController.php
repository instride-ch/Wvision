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

use Wvision\Model\Configuration;
use Wvision\Controller\Action;

class Wvision_NotificationController extends \Wvision\Controller\Action
{
    /**
     * init controller
     *
     * @return mixed Pimcore\Layout
     */
    public function init()
    {
        parent::init();
        $this->enableLayout();
    }

    /**
     * returns all the notification configs
     *
     * @return array Configuration
     */
    public function getConfigurationsAction()
    {
        $this->renderScript('notification/ajax-catcher.php');

        $configs = new Configuration\Listing();
        $configs->getConfigurations();

        $config = [];
        foreach ($configs->configurations as $key => $value) {
            if (strpos($value->key, 'APPLICATION.NOTIFICATION') !== false) {
                $config[$value->key] = $value->data;
            }
        }

        if (!empty($config)) {
            return $this->_helper->json($config);
        }

        return false;
    }
}
