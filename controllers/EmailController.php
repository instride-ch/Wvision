<?php

use Pimcore\Logger;
use Wvision\Controller\Action;

/**
 * Wvision_EmailController Class
 */
class Wvision_EmailController extends Action
{
    /**
     * Mail Template Method
     */
    public function templateAction()
    {
        $this->disableLayout();
    }
}
