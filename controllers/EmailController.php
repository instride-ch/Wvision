<?php
use Wvision\Controller\Action;

/**
 * Wvision_EmailController
 */
class Wvision_EmailController extends Action
{
    /**
     * Template Action
     *
     * @return string /views/scripts/email/template.php
     */
    public function templateAction()
    {
        $this->disableLayout();
    }
}
