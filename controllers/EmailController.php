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

use Wvision\Controller\Action;

class Wvision_EmailController extends Action
{
    /**
     * Template Action
     *
     * @return string /views/scripts/email/template.php
     */
    public function authTemplateAction()
    {
        $this->disableLayout();
    }

    /**
     * Template Action
     *
     * @return string /views/scripts/email/template.php
     */
    public function newsletterTemplateAction()
    {
        $this->disableLayout();
    }
}
