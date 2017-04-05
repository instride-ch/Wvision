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

use Pimcore\Document\Newsletter\SendingParamContainer;
use Wvision\Model\Configuration;
use Pimcore\Mail;
use Pimcore\Model\Document;
use Pimcore\Model;
use Pimcore\Logger;

class Newsletter
{

    /**
     * @param Mail $mail
     * @param SendingParamContainer $sendingContainer
     */
    public static function sendNewsletterDocumentBasedMail(Mail $mail, SendingParamContainer $sendingContainer)
    {
        $mailAddress = $sendingContainer->getEmail();
        if (!empty($mailAddress)) {
            $mail->setTo($mailAddress);
            $mail->sendWithoutRendering(static::getTransportForMail($mail));

            Logger::info("Sent newsletter to: " . self::obfuscateEmail($mailAddress) . " [" . $mail->getDocument()->getId() . "]");
        } else {
            Logger::warn("No E-Mail Address given - cannot send mail. [" . $mail->getDocument()->getId() . "]");
        }
    }

    protected static function obfuscateEmail($email)
    {
        $email = substr_replace($email, ".xxx", strrpos($email, "."));

        return $email;
    }

    /**
     * @param Mail $mail
     * @return null|\Zend_Mail_Transport_Smtp
     */
    public static function getTransportForMail(Mail $mail) {
        $document = $mail->getDocument();

        $site = static::getSiteForDocument($document);

        //Only change SMTP stuff if document is part of a site
        if ($site instanceof Model\Site) {
            $siteId = $site->getId();

            $config = [];

            $configPrefix = "APPLICATION.MULTISITE.$siteId";
            $name = Configuration::get("$configPrefix.SMTP.NAME");
            $ssl = Configuration::get("$configPrefix.SMTP.SSL");
            $port = Configuration::get("$configPrefix.SMTP.PORT");
            $host = Configuration::get("$configPrefix.SMTP.HOST");
            $method = Configuration::get("$configPrefix.SMTP.AUTH.METHOD");
            $username = Configuration::get("$configPrefix.SMTP.AUTH.USERNAME");
            $password = Configuration::get("$configPrefix.SMTP.AUTH.PASSWORD");

            if ($host) {
                if ($name) {
                    $config['name'] = $name;
                }
                if ($ssl) {
                    $config['ssl'] = $ssl;
                }
                if ($port) {
                    $config['port'] = $port;
                }
                if ($method) {
                    $config['auth'] = $method;
                    $config['username'] = $username;
                    $config['password'] = $password;
                }

                return new \Zend_Mail_Transport_Smtp($host, $config);
            }
        }

        return null;
    }

    /**
     * @param Document $document
     * @return null|Model\Site
     */
    public static function getSiteForDocument(Document $document) {
        if ($document instanceof Document) {
            do {
                try {
                    $site = Model\Site::getByRootId($document->getId());

                    if ($site instanceof Model\Site) {
                        return $site;
                    }
                } catch (\Exception $x) {

                }

                $document = $document->getParent();
            } while ($document instanceof Document);
        }

        return null;
    }
}