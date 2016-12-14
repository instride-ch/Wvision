<?php
 /**
  * w-vision
  *
  * LICENSE
  *
  * For the full copyright and license information, please view the LICENSE.md
  * file that is distributed with this source code.
  *
  * @copyright  Copyright (c) 2015-2016 Woche-Pass AG (http://www.w-vision.ch)
  */

namespace Wvision;

use Pimcore\Model\Document;
use Pimcore\Mail;
use Pimcore\Tool;

class Email
{
    /**
     * Send email to user and admin
     *
     * @param  string $username email address of user
     * @param  array  $params   Params for email
     * @return bool             set status to true
     */
    public static function send($email, $params)
    {
        if ($params && $params["document"]) {
            $document = $params["document"];

            if ($document->getProperty("userEmailDocument")) {
                $userDocument = $document->getProperty("userEmailDocument");
            } else {
                // TODO: Notification missing property
            }

            if ($document->getProperty("adminEmailDocument")) {
                $adminDocument = $document->getProperty("adminEmailDocument");
            } else {
                // TODO: Notification missing property
            }
        }

        if (Mail::isValidEmailAddress($email)) {
            if ($userDocument instanceof Document\Email) {
                try {
                    $userMail = new Mail();
                    $userMail->setDocument($userDocument);
                    $userMail->setParams($params);
                    $userMail->addTo($email);
                    $userMail->send();
                } catch (\Exception $e) {
                    // TODO: Notification mail not sent
                }
            } else {
                // TODO: Notification not an email document
            }

            if ($adminDocument instanceof Document\Email) {
                try {
                    $adminMail = new Mail();
                    $adminMail->setDocument($adminDocument);
                    $adminMail->setParams($params);
                    $adminMail->send();
                } catch (\Exception $e) {
                    // TODO: Notification mail not sent
                }
            } else {
                // TODO: Notification not an email document
            }

			// TODO: Notification mail sent
        } else {
            // TODO: Notification not a valid email address given
        }
    }
}
