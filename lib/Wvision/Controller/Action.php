<?php

namespace Wvision\Controller;

class Action extends \Website\Controller\Action {
    /**
     * Init CoreShop Controller.
     */
    public function init()
    {
        parent::init();

        $this->view->setScriptPath(
            array_merge(
                $this->view->getScriptPaths(),
                array(
                    PIMCORE_WEBSITE_PATH.'/views/scripts/wvision',
                )
            )
        );
    }
}
