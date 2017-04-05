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

//Need require here
require_once(PIMCORE_DOCUMENT_ROOT . '/pimcore/modules/admin/controllers/NewsletterController.php');

use Pimcore\Model\Document;
use Pimcore\Model\Tool;

class Wvision_Admin_NewsletterController extends Admin_NewsletterController
{
    public function sendAction()
    {
        $document = Document\Newsletter::getById($this->getParam("id"));

        if (Tool\TmpStore::get($document->getTmpStoreId())) {
            throw new Exception("newsletter sending already in progress, need to finish first.");
        }

        $document = Document\Newsletter::getById($this->getParam("id"));

        Tool\TmpStore::add($document->getTmpStoreId(), [
            'documentId' => $document->getId(),
            'addressSourceAdapterName' => $this->getParam("addressAdapterName"),
            'adapterParams' => json_decode($this->getParam("adapterParams"), true),
            'inProgress' => false,
            'progress' => 0
        ], 'newsletter');

        \Pimcore\Tool\Console::runPhpScriptInBackground(realpath(PIMCORE_PATH . DIRECTORY_SEPARATOR . "cli" . DIRECTORY_SEPARATOR . "console.php"), "wvision:newsletter-document-send " . escapeshellarg($document->getTmpStoreId()) . " " . escapeshellarg(\Pimcore\Tool::getHostUrl()), PIMCORE_LOG_DIRECTORY . DIRECTORY_SEPARATOR . "newsletter-sending-output.log");
        $this->_helper->json(["success" => true]);
    }


    public function sendTestAction()
    {
        $document = Document\Newsletter::getById($this->getParam("id"));
        $addressSourceAdapterName = $this->getParam("addressAdapterName");
        $adapterParams = json_decode($this->getParam("adapterParams"), true);

        $adapterClass = "\\Pimcore\\Document\\Newsletter\\AddressSourceAdapter\\" . ucfirst($addressSourceAdapterName);

        /**
         * @var $addressAdapter \Pimcore\Document\Newsletter\AddressSourceAdapterInterface
         */
        $addressAdapter = new $adapterClass($adapterParams);

        $sendingContainer = $addressAdapter->getParamsForTestSending($this->getParam("testMailAddress"));

        $mail = \Pimcore\Tool\Newsletter::prepareMail($document);
        \Wvision\Tool\Newsletter::sendNewsletterDocumentBasedMail($mail, $sendingContainer);

        $this->_helper->json(["success" => true]);
    }
}