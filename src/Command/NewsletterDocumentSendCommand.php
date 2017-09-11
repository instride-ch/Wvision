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

namespace WvisionBundle\Command;

use Pimcore\Bundle\CoreBundle\Command\InternalNewsletterDocumentSendCommand;
use Pimcore\Document\Newsletter\AddressSourceAdapterInterface;
use Pimcore\Model;
use Pimcore\Tool\Newsletter;

class NewsletterDocumentSendCommand extends InternalNewsletterDocumentSendCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('wvision:newsletter-document-send')
            ->setDescription('Send multi-site capable newsletter')
            ->addArgument('sendingId')->addArgument('hostUrl');
    }

    /**
     * {@inheritdoc}
     */
    protected function doSendMailInBatchMode(Model\Document\Newsletter $document, AddressSourceAdapterInterface $addressAdapter, $sendingId, $hostUrl)
    {
        $logger = \Pimcore::getContainer()->get('pimcore.app_logger');
        $sendingParamContainers = $addressAdapter->getMailAddressesForBatchSending();

        $currentCount = 0;
        $totalCount = $addressAdapter->getTotalRecordCount();

        //calculate page size based on total item count - with min page size 3 and max page size 10
        $fifth = $totalCount / 5;
        $pageSize = $fifth > 10 ? 10 : ($fifth < 3 ? 3 : intval($fifth));

        foreach ($sendingParamContainers as $sendingParamContainer) {
            $mail = Newsletter::prepareMail($document, $sendingParamContainer, $hostUrl);
            $tmpStore = Model\Tool\TmpStore::get($sendingId);

            if (empty($tmpStore)) {
                $logger->warning(
                    sprintf('Sending configuration for sending ID %s was deleted. Cancelling sending process.', $sendingId), [
                        'relatedObject' => $document,
                        'component' => 'NewsletterDocumentSendCommand'
                    ]
                );
                exit;
            }

            if ($currentCount % $pageSize == 0) {
                $logger->info(
                    sprintf('Sending newsletter %s / %s [%s].', $currentCount, $totalCount, $document->getId()), [
                        'relatedObject' => $document,
                        'component' => 'NewsletterDocumentSendCommand'
                    ]
                );

                $data = $tmpStore->getData();
                $data['progress'] = round($currentCount / $totalCount * 100, 2);

                $tmpStore->setData($data);
                $tmpStore->update();

                \Pimcore::collectGarbage();
            }

            try {
                $this->getContainer()->get('WvisionBundle\Tool\MultiSmtpNewsletter')->send($mail, $sendingParamContainer);
            } catch (\Exception $e) {
                $logger->error(
                    sprintf('Exception while sending newsletter: %s', $e->getMessage()), [
                        'relatedObject' => $document,
                        'component' => 'NewsletterDocumentSendCommand'
                    ]
                );
            }

            $currentCount++;
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function doSendMailInSingleMode(Model\Document\Newsletter $document, AddressSourceAdapterInterface $addressAdapter, $sendingId, $hostUrl)
    {
        $logger = \Pimcore::getContainer()->get('pimcore.app_logger');
        $totalCount = $addressAdapter->getTotalRecordCount();

        //calculate page size based on total item count - with min page size 3 and max page size 10
        $fifth = $totalCount / 5;
        $limit = $fifth > 10 ? 10 : ($fifth < 3 ? 3 : intval($fifth));
        $offset = 0;
        $hasElements = true;

        while ($hasElements) {
            $tmpStore = Model\Tool\TmpStore::get($sendingId);

            $data = $tmpStore->getData();

            $logger->info(
                sprintf('Sending newsletter %s / %s [%s].', $hasElements, $totalCount, $document->getId()), [
                    'relatedObject' => $document,
                    'component' => 'NewsletterDocumentSendCommand'
                ]
            );

            $data['progress'] = round($offset / $totalCount * 100, 2);
            $tmpStore->setData($data);
            $tmpStore->update();

            $sendingParamContainers = $addressAdapter->getParamsForSingleSending($limit, $offset);
            foreach ($sendingParamContainers as $sendingParamContainer) {
                try {
                    $mail = Newsletter::prepareMail($document, $sendingParamContainer, $hostUrl);
                    $this->getContainer()->get('WvisionBundle\Tool\MultiSmtpNewsletter')->send($mail, $sendingParamContainer);
                } catch (\Exception $e) {
                    $logger->error(
                        sprintf('Exception while sending newsletter: %s', $e->getMessage()), [
                            'relatedObject' => $document,
                            'component' => 'NewsletterDocumentSendCommand'
                        ]
                    );
                }

                if (empty($tmpStore)) {
                    $logger->warning(
                        sprintf('Sending configuration for sending ID %s was deleted. Cancelling sending process.', $sendingId), [
                            'relatedObject' => $document,
                            'component' => 'NewsletterDocumentSendCommand'
                        ]
                    );
                    exit;
                }
            }

            $offset += $limit;
            $hasElements = count($sendingParamContainers);

            \Pimcore::collectGarbage();
        }
    }
}