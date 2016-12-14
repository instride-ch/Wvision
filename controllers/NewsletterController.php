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
use Wvision\Tool\Notification;
use Pimcore\Tool\Newsletter;
use Pimcore\Logger;
use Pimcore\Model;

class Wvision_NewsletterController extends \Wvision\Controller\Action
{
    /**
     * SendGrid client
     *
     * @var object Sendgrid\Client
     */
    protected $client;

    /**
     * init controller
     *
     * @return layout
     */
    public function init()
    {
        parent::init();
        $this->enableLayout();

        $this->client = Sendgrid\Factory::getDefaultClient();
    }

    /**
     * user subscribes to newsletter
     *
     * @return mixed
     */
    public function subscribeAction()
    {
        $newsletter = new Newsletter('NewsletterUser');
        $params = $this->getAllParams();
        $document = $params['document'];

        if ($newsletter->checkParams($params)) {
            try {
                $params['parentId'] = 1;
                $newsletterFolder = Model\Object::getByPath(Configuration::get('APPLICATION.NEWSLETTER.USER_FOLDER'));

                if ($newsletterFolder) {
                    $params['parentId'] = $newsletterFolder->getId();
                }

                $user = $newsletter->subscribe($params);

                if ($document->getProperty('confirmationEmail')) {
                    $newsletter->sendConfirmationMail($user, $document->getProperty('confirmationEmail'));
                } else {
                    throw new \Exception('Document "' . $document->getKey() . '" is missing "confirmationEmail" property');
                }

                $user->save();

                Notification::success('Newsletter abonniert', 'Sie haben sich erfolgreich für unseren Newsletter angemeldet');
            } catch (\Exception $e) {
                Logger::err($e);
            }
        }
    }

    /**
     * user confirms subscription
     *
     * @return mixed
     */
    public function confirmAction()
    {
        $newsletter = new Newsletter('NewsletterUser');

        if ($newsletter->confirm($this->getParam('token'))) {
            Notification::success('Abonnement bestätigt', 'Sie haben Ihr Newsletter-Abo erfolgreich bestätigt');
        }
    }

    /**
     * user unsubscribes from newsletter
     *
     * @return mixed
     */
    public function unsubscribeAction()
    {
        $success = false;
        $newsletter = new Newsletter('NewsletterUser');

        if ($this->getParam('email')) {
            $success = $newsletter->unsubscribeByEmail($this->getParam('email'));
        }

        if ($this->getParam('token')) {
            $success = $newsletter->unsubscribeByToken($this->getParam('token'));
        }

        if ($success) {
            Notification::success('Newsletter abgemeldet', 'Sie erhalten ab sofort keine Newsletter mehr');
        }
    }

    /**
     * user unsubscribes from newsletter
     *
     * @return mixed
     */
    public function editAction()
    {
        $newsletter = new Newsletter('NewsletterUser');
        $params = $this->getAllParams();
        $document = $params['document'];

        if ($this->getParam('token')) {
            $user = $newsletter->getObjectByToken($this->getParam('token'));
            $this->view->user = $user;
        }

        if ($newsletter->checkParams($params)) {
            try {
                $user = $newsletter->subscribe($params);

                if ($document->getProperty('confirmationEmail')) {
                    $newsletter->sendConfirmationMail($user, $document->getProperty('confirmationEmail'));
                } else {
                    throw new \Exception('Document "' . $document->getKey() . '" is missing "confirmationEmail" property');
                }

                $user->save();

                Notification::success('Daten bestätigt', 'Ihre Daten wurden erfolgreich mutiert');
            } catch (\Exception $e) {
                Logger::err($e);
            }
        }
    }
}
