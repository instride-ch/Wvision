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
use Pimcore\Mail;
use Pimcore\Model\Document;
use Pimcore\Model\Site;
use WvisionBundle\Configuration\Configuration;

class MultiSmtpNewsletter
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @param Configuration $configuration
     */
    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function send(Mail $mail, SendingParamContainer $sendingContainer)
    {
        $logger = \Pimcore::getContainer()->get('pimcore.app_logger');
        $mailAddress = $sendingContainer->getEmail();

        if (!empty($mailAddress)) {
            $mail->setTo($mailAddress);

            $mailer = $this->getTransportForMail($mail);
            //check if newsletter specific mailer is needed
            if (Config::getSystemConfig()->newsletter->usespecific && is_null($mailer)) {
                $mailer = \Pimcore::getContainer()->get('swiftmailer.mailer.newsletter_mailer');
            }

            $mail->sendWithoutRendering($mailer);

            $logger->info(
                sprintf('Sent newsletter to: %s [%s]', static::obfuscateEmail($mailAddress), $mail->getDocument()->getId()), [
                    'relatedObject' => $mail->getDocument(),
                    'component' => 'MultiSmtpNewsletter'
                ]
            );
        } else {
            $logger->warning(
                sprintf('No email address given - cannot send newsletter. [%s]', $mail->getDocument()->getId()), [
                    'relatedObject' => $mail->getDocument(),
                    'component' => 'MultiSmtpNewsletter'
                ]
            );
        }
    }

    /**
     * Returns a \Swift_Mailer object for sending the newsletters.
     *
     * @param Mail $mail
     * @return null|\Swift_Mailer
     */
    public function getTransportForMail(Mail $mail)
    {
        $logger = \Pimcore::getContainer()->get('pimcore.app_logger');
        $document = $mail->getDocument();
        $site = $this->getSiteForDocument($document);

        // Only change SMTP stuff if document is part of a site
        if ($site instanceof Site) {
            $siteMainDomain = $site->getMainDomain();
            $config = $this->configuration->getConfig('newsletter');

            if (!is_null($config['sites']) && array_key_exists($siteMainDomain, $config['sites'])) {
                $config = $config['sites'][$siteMainDomain];
            } else {
                $config = $config['default'];
            }

            $host = $config['smtp']['host'];
            $security = $config['smtp']['security'];
            $port = $config['smtp']['port'];
            $name = $config['smtp']['name'];
            $authMethod = $config['smtp']['auth_method'];
            $user = $config['smtp']['user'];
            $password = $config['smtp']['password'];

            if ($host) {
                if ($authMethod) {
                    $config['auth'] = $authMethod;
                    $config['username'] = $user;
                    $config['password'] = $password;
                }

                $logger->info(
                    sprintf('Got Transport for Multi SMTP: %s, %s.', $name, $user), [
                        'relatedObject' => $document,
                        'component' => 'MultiSmtpNewsletter'
                    ]
                );

                $transport = (new \Swift_SmtpTransport($host, $port, $security))
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
     *
     * TODO: Maybe refactor to be in a separate helper class (Separation of concerns)
     */
    public function getSiteForDocument(Document $document) {
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

    /**
     * @param $email
     *
     * @return mixed
     *
     * TODO: Maybe refactor to be in a separate helper class (Separation of concerns)
     */
    private static function obfuscateEmail($email)
    {
        $email = substr_replace($email, '.xxx', strrpos($email, '.'));

        return $email;
    }
}