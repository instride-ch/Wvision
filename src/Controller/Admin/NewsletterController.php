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

namespace WvisionBundle\Controller\Admin;

use Pimcore\Tool\Console;
use Pimcore\Tool\Newsletter;
use Pimcore\Model\Document;
use Pimcore\Model\Tool;
use Symfony\Component\HttpFoundation\Request;

class NewsletterController extends \Pimcore\Bundle\AdminBundle\Controller\Admin\NewsletterController
{
    /**
     * {@inheritdoc}
     */
    public function sendAction(Request $request)
    {
        $document = Document\Newsletter::getById($request->get('id'));

        if (Tool\TmpStore::get($document->getTmpStoreId())) {
            throw new \Exception('newsletter sending already in progress, need to finish first.');
        }

        $document = Document\Newsletter::getById($request->get('id'));

        Tool\TmpStore::add($document->getTmpStoreId(), [
            'documentId' => $document->getId(),
            'addressSourceAdapterName' => $request->get('addressAdapterName'),
            'adapterParams' => json_decode($request->get('adapterParams'), true),
            'inProgress' => false,
            'progress' => 0
        ], 'newsletter');

        Console::runPhpScriptInBackground(
            realpath(PIMCORE_PROJECT_ROOT . DIRECTORY_SEPARATOR . 'bin' . DIRECTORY_SEPARATOR . 'console'),
            'wvision:newsletter-document-send ' . escapeshellarg($document->getTmpStoreId()) . ' ' . escapeshellarg(\Pimcore\Tool::getHostUrl()),
            PIMCORE_LOG_DIRECTORY . DIRECTORY_SEPARATOR . 'newsletter-sending-output.log'
        );

        return $this->json(['success' => true]);
    }

    /**
     * {@inheritdoc}
     */
    public function sendTestAction(Request $request)
    {
        $document = Document\Newsletter::getById($request->get('id'));
        $addressSourceAdapterName = $request->get('addressAdapterName');
        $adapterParams = json_decode($request->get('adapterParams'), true);

        $adapterClass = '\\Pimcore\\Document\\Newsletter\\AddressSourceAdapter\\' . ucfirst($addressSourceAdapterName);

        /**
         * @var $addressAdapter \Pimcore\Document\Newsletter\AddressSourceAdapterInterface
         */
        $addressAdapter = new $adapterClass($adapterParams);

        $sendingContainer = $addressAdapter->getParamsForTestSending($request->get('testMailAddress'));

        $mail = Newsletter::prepareMail($document);
        $this->container->get('WvisionBundle\Tool\MultiSmtpNewsletter')->send($mail, $sendingContainer);

        return $this->json(['success' => true]);
    }
}