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

use Wvision\Model\Configuration;
use Wvision\Tool\Authentication;
use Wvision\Tool\Notification;
use Pimcore\Logger;
use Pimcore\Model;

class Wvision_AuthenticationController extends \Wvision\Controller\Action
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
     * login to protected area
     *
     * @return object \Zend_Exception
     */
	public function loginAction()
	{
        $auth = new Authentication('Kunde');

        if ($this->getParam('email') && $this->getParam('password')) {
            try {
                $user = $auth->authenticate($this->getParam('email'), $this->getParam('password'));

                if ($user) {
                    $session = $auth->getSession();
                    $session->user = $user->getId();

                    Notification::success('Login erfolgreich', 'Sie sind nun angemeldet');
                }
            } catch (\Exception $e) {
                Logger::err($e);
            }
        }
	}

    /**
     * register a new user
     *
     * @return object new user
     */
	public function registerAction()
	{
        $auth = new Authentication('Kunde');
        $params = $this->getAllParams();
        $document = $params['document'];

        if ($auth->checkParams($params)) {
            try {
                $params['parentId'] = 1;
                $userFolder = Model\Object::getByPath(Configuration::get('APPLICATION.AUTH.USER_FOLDER'));

                if ($userFolder) {
                    $params['parentId'] = $userFolder->getId();
                }

                $user = $auth->register($params);

                if ($document->getProperty('confirmationEmail')) {
                    $auth->sendConfirmationMail($user, $document->getProperty('confirmationEmail'));
                } else {
                    throw new \Exception('Document "' . $document->getKey() . '" is missing "confirmationEmail" property');
                }

                $user->save();

                Notification::success('Registration abgeschlossen', 'Sie haben sich erfolgreich registriert');
            } catch (\Exception $e) {
                Logger::err($e);
            }
        }
	}

    /**
     * confirm new login
     *
     * @return object confirmed user
     */
    public function confirmAction()
    {
        $auth = new Authentication('Kunde');

        if ($this->getParam('token')) {
            $auth->confirm($this->getParam('token'));
        }
    }
}
