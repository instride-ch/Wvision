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

class Wvision_ErrorController extends \Wvision\Controller\Action
{
    /**
     * init controller
     *
     * @return layout
     */
    public function init()
    {
        parent::init();
        $this->enableLayout();
    }

	/**
	 * assigns error code and message to view
	 *
	 * @return mixed error code and message
	 */
	public function errorAction()
	{
        $errors = $this->getParam("error_handler");
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                $responseMessage = "Seite nicht gefunden";
            	break;
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_OTHER:
                $responseMessage = $errors->exception->getMessage();
            	break;
            default:
                $responseMessage = "Anwendungsfehler";
            	break;
        }

        if (!$errors || !$errors instanceof ArrayObject) {
            $responseMessage = "Keine Fehler";
        }
        
        $responseCode = $this->getResponse()->getHttpResponseCode();

        $this->view->errorCode = $responseCode;
        $this->view->errorMessage = $responseMessage;
	}
}
