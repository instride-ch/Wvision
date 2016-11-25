<?php
namespace Wvision\Controller;

/**
 * Action
 */
class Action extends \Website\Controller\Action
{
    /**
     * Init Wvision Controller
     */
    public function init()
    {
        parent::init();

        $this->view->setScriptPath(
            array_merge(
                $this->view->getScriptPaths(),
                array(PIMCORE_WEBSITE_PATH . '/views/scripts/wvision')
            )
        );
    }
}
