<?php

namespace Wvision\Command;

use Pimcore\Console\Command\InternalNewsletterDocumentSendCommand;
use Pimcore\Document\Newsletter\AddressSourceAdapterInterface;
use Pimcore\Model;
use Pimcore\Logger;
use Wvision\Tool\Newsletter;

class NewsletterDocumentSendCommand extends InternalNewsletterDocumentSendCommand
{
    protected function configure()
    {
        $this
            ->setName('wvision:newsletter-document-send')
            ->setDescription('For Wvision internal use only')
            ->addArgument("sendingId")->addArgument("hostUrl");
    }

    protected function doSendMailInBatchMode(Model\Document\Newsletter $document, AddressSourceAdapterInterface $addressAdapter, $sendingId)
    {
        $mail = \Pimcore\Tool\Newsletter::prepareMail($document);
        $sendingParamContainers = $addressAdapter->getMailAddressesForBatchSending();

        $currentCount = 0;
        $totalCount = $addressAdapter->getTotalRecordCount();

        //calculate page size based on total item count - with min page size 3 and max page size 10
        $fifth = $totalCount / 5;
        $pageSize = $fifth > 10 ? 10 : ($fifth < 3 ? 3 : intval($fifth));

        foreach ($sendingParamContainers as $sendingParamContainer) {
            $tmpStore = Model\Tool\TmpStore::get($sendingId);

            if (empty($tmpStore)) {
                Logger::warn("Sending configuration for sending ID $sendingId was deleted. Cancelling sending process.");
                exit;
            }

            if ($currentCount % $pageSize == 0) {
                Logger::info("Sending newsletter " . $currentCount . " / " . $totalCount. " [" . $document->getId(). "]");
                $data = $tmpStore->getData();
                $data['progress'] = round($currentCount / $totalCount * 100, 2);
                $tmpStore->setData($data);
                $tmpStore->update();
                \Pimcore::collectGarbage();
            }

            Newsletter::sendNewsletterDocumentBasedMail($mail, $sendingParamContainer);

            $currentCount++;
        }
    }

    protected function doSendMailInSingleMode(Model\Document\Newsletter $document, AddressSourceAdapterInterface $addressAdapter, $sendingId)
    {
        $totalCount = $addressAdapter->getTotalRecordCount();

        //calculate page size based on total item count - with min page size 3 and max page size 10
        $fifth = $totalCount / 5;
        $limit = $fifth > 10 ? 10 : ($fifth < 3 ? 3 : intval($fifth));
        $offset = 0;
        $hasElements = true;

        while ($hasElements) {
            $tmpStore = Model\Tool\TmpStore::get($sendingId);

            $data = $tmpStore->getData();

            Logger::info("Sending newsletter " . $hasElements . " / " . $totalCount. " [" . $document->getId(). "]");

            $data['progress'] = round($offset / $totalCount * 100, 2);
            $tmpStore->setData($data);
            $tmpStore->update();

            $sendingParamContainers = $addressAdapter->getParamsForSingleSending($limit, $offset);
            foreach ($sendingParamContainers as $sendingParamContainer) {
                $mail = \Pimcore\Tool\Newsletter::prepareMail($document, $sendingParamContainer);
                Newsletter::sendNewsletterDocumentBasedMail($mail, $sendingParamContainer);


                if (empty($tmpStore)) {
                    Logger::warn("Sending configuration for sending ID $sendingId was deleted. Cancelling sending process.");
                    exit;
                }
            }

            $offset += $limit;
            $hasElements = count($sendingParamContainers);

            \Pimcore::collectGarbage();
        }
    }
}