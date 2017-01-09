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

class Wvision_Admin_TestController extends \Pimcore\Controller\Action\Admin
{
    public function testAction() {
        $install = new \Wvision\Plugin\Install();
        $install->installSystemSettings();

        exit;
    }
}