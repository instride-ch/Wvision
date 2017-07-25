<?php

namespace Wvision\Command;

use Pimcore\Console\AbstractCommand;
use Pimcore\Document\Newsletter\AddressSourceAdapterInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Pimcore\Model;
use Pimcore\Logger;
use Pimcore\Console\Command\InternalNewsletterDocumentSendCommand;
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

    protected function doSendMailInBatchMode(Model\Document\Newsletter $document, AddressSourceAdapterInterface $addressAdapter, $sendingId, $hostUrl)
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
                Logger::info("Sending newsletter " . $currentCount . " / " . $totalCount . " [" . $document->getId() . "]");
                $data = $tmpStore->getData();
                $data['progress'] = round($currentCount / $totalCount * 100, 2);
                $tmpStore->setData($data);
                $tmpStore->update();
                \Pimcore::collectGarbage();
            }

            try {
                Newsletter::sendNewsletterDocumentBasedMail($mail, $sendingParamContainer);
            } catch (\Exception $e) {
                Logger::err(sprintf('Exception while sending to "%s" newsletter: %s', implode(",", $mail->getRecipients()), $e->getMessage()), $e);
            }

            $currentCount++;
        }
    }

    protected function doSendMailInSingleMode(Model\Document\Newsletter $document, AddressSourceAdapterInterface $addressAdapter, $sendingId, $hostUrl)
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

            Logger::info("Sending newsletter " . $hasElements . " / " . $totalCount . " [" . $document->getId() . "]");

            $data['progress'] = round($offset / $totalCount * 100, 2);
            $tmpStore->setData($data);
            $tmpStore->update();

            $sendingParamContainers = $addressAdapter->getParamsForSingleSending($limit, $offset);
            foreach ($sendingParamContainers as $sendingParamContainer) {
                $mail = \Pimcore\Tool\Newsletter::prepareMail($document, $sendingParamContainer);

                try {
                    Newsletter::sendNewsletterDocumentBasedMail($mail, $sendingParamContainer);
                } catch (\Exception $e) {
                    Logger::err(sprintf('Exception while sending to "%s" newsletter: %s', implode(",", $mail->getRecipients()), $e->getMessage()), $e);
                }


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