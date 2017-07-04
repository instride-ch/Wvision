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

class Email
{
    /**
     * Send email to user and admin
     *
     * @param $email
     * @param null $adminEmail
     * @param  array $params Params for email
     * @param bool $userDocument
     * @param bool $adminDocument
     * @param bool $file
     * @param null $site
     * @return bool set status to true
     * @throws \Exception
     * @internal param string $username email address of user
     */
    public static function send($email, $adminEmail = null, $params, $userDocument = false, $adminDocument = false, $file = false, $site = null)
    {
        $success = false;

        if ($params && $params["document"]) {
            $document = $params["document"];

            if (!$userDocument) {
                if ($document->getProperty("userEmailDocument")) {
                    $userDocument = $document->getProperty("userEmailDocument");
                }
            }

            if (!$adminDocument) {
                if ($document->getProperty("adminEmailDocument")) {
                    $adminDocument = $document->getProperty("adminEmailDocument");
                }
            }

            if (!$file) {
                if ($document->getProperty('attachment')) {
                    $file = $document->getProperty('attachment');
                }
            }

            if ($file) {
                $attachment = new \Zend_Mime_Part(file_get_contents(PIMCORE_ASSET_DIRECTORY . $file->getFullPath()));
                $attachment->type = $file->mimetype;
                $attachment->encoding = \Zend_Mime::ENCODING_BASE64;
                $attachment->id = $file->id;
                $attachment->disposition = \Zend_Mime::DISPOSITION_ATTACHMENT;
                $attachment->filename = basename($file->filename);
            }
        }

        if ($userDocument && $userDocument instanceof \Pimcore\Model\Document\Email && Mail::isValidEmailAddress($email)) {
            try {
                $userMail = new Mail();
                $userMail->setEnableLayoutOnPlaceholderRendering(false);
                $userMail->setDocument($userDocument);
                $userMail->setParams($params);
                $userMail->addTo($email);

                if ($attachment) {
                    $userMail->addAttachment($attachment);
                }

                $userMail->send();

                $success = true;
            } catch (\Exception $e) {
                throw new \Exception($e);
            }
        }

        if ($adminDocument && $adminDocument instanceof \Pimcore\Model\Document\Email) {
            try {
                $adminMail = new Mail();
                $adminMail->setEnableLayoutOnPlaceholderRendering(false);
                $adminMail->setDocument($adminDocument);
                $adminMail->setParams($params);

                if ($adminEmail) {
                    $adminMail->setTo($adminEmail);
                }

                if ($attachment) {
                    $adminMail->addAttachment($attachment);
                }

                $adminMail->send();

                $success = true;
            } catch (\Exception $e) {
                throw new \Exception($e);
            }
        }

        if ($success) {
            return true;
        }

        return false;
    }
}
