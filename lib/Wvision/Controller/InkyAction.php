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

class InkyAction extends Action
{
    /**
     * Init controller
     */
    public function init()
    {
        parent::init();
    }

    public function postDispatch()
    {
        parent::postDispatch();

        if ($this->getResponse()->canSendHeaders()) {
            $this->getResponse()->setHeader("Content-Type", "text/inky; charset=UTF-8", true);
        }
    }
}
