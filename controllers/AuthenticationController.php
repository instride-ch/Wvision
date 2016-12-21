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

                    if ($this->getRequest()->isXmlHttpRequest()) {
                        $this->_helper->json([
                            'title' => 'Login erfolgreich',
                            'message' => 'Sie sind nun angemeldet'
                        ]);
                    } else {
                        if ($this->getRequest()->isGet()) {
                            $username = $this->getParam('username');
                            $apiKey = $this->getParam('apikey');
                            $pimcoreUser = \Pimcore\Model\User::getByName($username);

                            if ($pimcoreUser instanceof \Pimcore\Model\User) {
                                if ($apiKey === $pimcoreUser->getApiKey()) {
                                    $this->_helper->json([
                                        'success' => true,
                                        'idnum' => $user->getIdnum(),
                                        'email' => $user->getEmail(),
                                        'company' => $user->getCompany(),
                                        'lastname' => $user->getLastname(),
                                        'firstname' => $user->getFirstname(),
                                        'address' => $user->getAddress(),
                                        'zip' => $user->getZip(),
                                        'city' => $user->getCity(),
                                        'phone' => $user->getPhone(),
                                        'fax' => $user->getFax()
                                    ]);
                                }
                            }
                        } else {
                            Notification::success('Login erfolgreich', 'Sie sind nun angemeldet');
                        }
                    }
                }

                $this->_helper->json(['success' => false]);
            } catch (\Exception $e) {
                Logger::err($e);
                $this->_helper->json(['success' => false]);
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

                if ($this->getRequest()->isXmlHttpRequest()) {
                    $this->_helper->json([
                        'title' => 'Registration abgeschlossen',
                        'message' => 'Sie haben sich erfolgreich registriert'
                    ]);
                } else {
                    Notification::success('Registration abgeschlossen', 'Sie haben sich erfolgreich registriert');
                }
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

    /**
     * edit the user's data
     *
     * @return object confirmed user
     */
    public function editAction()
    {
        $auth = new Authentication('Kunde');
        $params = $this->getAllParams();
        $document = $params['document'];
        $user = $auth->isAuthenticated();

        if ($auth->checkParams($params)) {
            try {
                $success = $auth->updateObject($user, $params);

                if ($success instanceof \Pimcore\Model\Object\Kunde) {
                    if ($this->getRequest()->isXmlHttpRequest()) {
                        $this->_helper->json([
                    		'title' => 'Daten abgeändert',
                    		'message' => 'Ihre Daten wurden erfolgreich aktualisiert'
                    	]);
                    } else {
                        Notification::success('Daten abgeändert', 'Ihre Daten wurden erfolgreich aktualisiert');
                    }
                }
            } catch (\Exception $e) {
                Logger::err($e);
            }
        }
    }

    /**
     * edit the user's data
     *
     * @return object confirmed user
     */
    public function logoutAction()
    {
        $auth = new Authentication('Kunde');

        if ($auth->unsetUser()) {
            if ($this->getRequest()->isXmlHttpRequest()) {
                $this->_helper->json([
                    'title' => 'Logout',
                    'message' => 'Sie wurden erfolgreich abgemeldet',
                    'redirect' => $this->document
                ]);
            } else {
                Notification::success('Logout', 'Sie wurden erfolgreich abgemeldet');
            }
        }
    }
}
