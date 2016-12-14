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

class Wvision_Admin_ConsoleController extends \Pimcore\Controller\Action\Admin
{
    /**
     * Force Update Action
     *
     * @return string /views/scripts/console/force-update.php
     */
    public function forceUpdateAction()
    {
        // reachable via http://your.domain/plugin/Wvision/admin_console/force-update
        // \Pimcore\Tool\Console::exec('composer update nothing ' . PIMCORE_DOCUMENT_ROOT . ' --ignore-platform-reqs');
        // \Pimcore\Tool\Console::execInBackground('composer update nothing ' . PIMCORE_DOCUMENT_ROOT . ' --ignore-platform-reqs > console.log');
    }

    /**
     * Console Action
     *
     * @return string /views/scripts/console/console.php
     */
    public function consoleAction()
    {
        // reachable via http://your.domain/plugin/Wvision/admin_console/console
    }
}
