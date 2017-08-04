<?php
/**
 * w-vision
 *
 * LICENSE
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that is distributed with this source code.
 *
 * @copyright  Copyright (c) 2017 Woche-Pass AG (https://www.w-vision.ch)
 */

namespace WvisionBundle\Tool;

use Pimcore\Config;
use Pimcore\Document\Newsletter\SendingParamContainer;
use Pimcore\Logger;
use Pimcore\Mail;
use Pimcore\Model\Document;
use Pimcore\Model\Site;
use WvisionBundle\Configuration\Configuration;

class Newsletter extends \Pimcore\Tool\Newsletter
{
    /**
     * {@inheritdoc}
     */
    public static function sendNewsletterDocumentBasedMail(Mail $mail, SendingParamContainer $sendingContainer)
    {
        $mailAddress = $sendingContainer->getEmail();
        if (!empty($mailAddress)) {
            $mail->setTo($mailAddress);

            $mailer = static::getTransportForMail($mail);
            //check if newsletter specific mailer is needed
            if (Config::getSystemConfig()->newsletter->usespecific && is_null($mailer)) {
                $mailer = \Pimcore::getContainer()->get('swiftmailer.mailer.newsletter_mailer');
            }

            $mail->sendWithoutRendering($mailer);

            Logger::info('Sent newsletter to: ' . self::obfuscateEmail($mailAddress) . ' [' . $mail->getDocument()->getId() . ']');
        } else {
            Logger::warn('No E-Mail Address given - cannot send mail. [' . $mail->getDocument()->getId() . ']');
        }
    }

    /**
     * Returns a \Swift_Mailer object for sending the newsletters.
     *
     * @param Mail $mail
     * @return null|\Swift_Mailer
     */
    public static function getTransportForMail(Mail $mail) {
        $document = $mail->getDocument();
        $site = static::getSiteForDocument($document);

        // Only change SMTP stuff if document is part of a site
        if ($site instanceof Site) {
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

                Logger::info(sprintf('Got Transport for Multi SMTP: %s, %s.', [$name, $username]));

                $transport = (new \Swift_SmtpTransport($host, $config['port'], $config['ssl']))
                    ->setUsername($config['username'])
                    ->setPassword($config['password'])
                    ->setAuthMode($config['auth']);

                return new \Swift_Mailer($transport);
            }
        }

        return null;
    }

    /**
     * Returns the nearest site for a document.
     *
     * @param Document $document
     * @return null|Site
     */
    public static function getSiteForDocument(Document $document) {
        if ($document instanceof Document) {
            do {
                try {
                    $site = Site::getByRootId($document->getId());

                    if ($site instanceof Site) {
                        return $site;
                    }
                } catch (\Exception $e) {
                }

                $document = $document->getParent();
            } while ($document instanceof Document);
        }

        return null;
    }
}