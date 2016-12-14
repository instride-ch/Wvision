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

namespace Wvision\Controller;

class Action extends \Website\Controller\Action
{
    /**
     * init controller
     *
     * @return mixed settings
     */
    public function init()
    {
        parent::init();

        if (strtolower($this->getRequest()->getModuleName()) !== "default") {
            $this->view->setScriptPath(
                array_merge(
                    $this->view->getScriptPaths(),
                    [PIMCORE_WEBSITE_PATH . "/views/scripts"]
                )
            );
        }

        $this->view->setScriptPath(
            array_merge(
                $this->view->getScriptPaths(),
                [PIMCORE_WEBSITE_PATH . '/views/scripts/wvision']
            )
        );

        $this->view->addHelperPath(PIMCORE_PLUGINS_PATH . "/Wvision/lib/Wvision/View/Helper", "Wvision\View\Helper");

        $this->enableLayout();
    }
}
