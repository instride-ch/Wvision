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

class Wvision_Admin_SettingsController extends \Pimcore\Controller\Action\Admin
{
    public function getAction()
    {
        $valueArray = [];

        $config = new \Wvision\Model\Configuration\Listing();

        foreach ($config->getConfigurations() as $c) {
            $valueArray[$c->getKey()] = $c->getData();
        }

        $response = array(
            'settings' => $valueArray
        );

        $this->_helper->json($response);
        $this->_helper->json(false);
    }

    public function setAction()
    {
        $values = \Zend_Json::decode($this->getParam('settings'));
        $values = array_htmlspecialchars($values);

        foreach ($values as $key => $value) {
            \Wvision\Model\Configuration::set($key, $value);
        }

        $this->_helper->json(array('success' => true));
    }
}
