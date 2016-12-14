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

namespace Wvision\Tool;

use Pimcore\Model\Document;
use Pimcore\Mail;
use Pimcore\Tool;
use Pimcore\Logger;

class Email
{
    /**
     * status of any method
     * @var boolean
     */
    protected $status = false;

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
                throw new \Exception('Document "' . $document->getKey() . '" is missing "userEmailDocument" property');
            }

            if ($document->getProperty("adminEmailDocument")) {
                $adminDocument = $document->getProperty("adminEmailDocument");
            } else {
                throw new \Exception('Document "' . $document->getKey() . '" is missing "adminEmailDocument" property');
            }

            if ($document->getProperty('attachment')) {
                $file = $document->getProperty('attachment');

                $attachment = new \Zend_Mime_Part(file_get_contents(PIMCORE_ASSET_DIRECTORY . $file->getFullPath()));
                $attachment->type = $file->mimetype;
                $attachment->encoding = \Zend_Mime::ENCODING_BASE64;
                $attachment->id = $file->id;
                $attachment->disposition = \Zend_Mime::DISPOSITION_ATTACHMENT;
                $attachment->filename = basename($file->filename);
            }
        }

        if (Mail::isValidEmailAddress($email)) {
            if ($userDocument instanceof Document\Email) {
                try {
                    $userMail = new Mail();
                    $userMail->setDocument($userDocument);
                    $userMail->setParams($params);
                    $userMail->addTo($email);

                    if ($attachment) {
                        $userMail->addAttachment($attachment);
                    }

                    $userMail->send();
                    $this->status = true;
                } catch (\Exception $e) {
                    Logger::err($e);
                }
            } else {
                throw new \Exception('Document "' . $userDocument->getKey() . '" is not an email document');
            }

            if ($adminDocument instanceof Document\Email) {
                try {
                    $adminMail = new Mail();
                    $adminMail->setDocument($adminDocument);
                    $adminMail->setParams($params);

                    if ($attachment) {
                        $adminMail->addAttachment($attachment);
                    }

                    $adminMail->send();
                    $this->status = true;
                } catch (\Exception $e) {
                    Logger::err($e);
                }
            } else {
                throw new \Exception('Document "' . $adminDocument->getKey() . '" is not an email document');
            }
        } else {
            throw new \Exception('"' . $email . '" is not a valid email address');
        }

        return $this->status;
    }
}
