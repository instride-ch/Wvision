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

use Pimcore\Model;
use Pimcore\Logger;

class Wvision_UtilitiesController extends \Wvision\Controller\Action
{
    /**
     * init controller
     *
     * @return layout
     */
    public function init()
    {
        parent::init();
        $this->enableLayout();
    }

    /**
     * displays the imprint of the page
     *
     * @return mixed
     */
    public function imprintAction()
    {
        $doc = $this->getParam('document');
        $config = new Model\WebsiteSetting\Listing();

        $this->view->block = $doc->getElement('article');
        $this->view->config = $config->load();
    }

    /**
     * creates a sitemap
     *
     * @return document all pages
     */
    public function sitemapAction()
    {
        set_time_limit(900);
        $this->view->initial = false;

        if ($this->getParam('doc')) {
            $doc = $this->getParam('doc');
        } else {
            $doc = $this->document->getProperty('mainNavStartNode');
            $this->view->initial = true;
        }

        Pimcore::collectGarbage();

        $this->view->doc = $doc;
    }

    /**
     * custom Google CSE search engine
     *
     * @return array search results
     */
    public function searchAction()
    {
        if ($this->getParam('q')) {
            try {
                $page = $this->getParam('page');
                if (empty($page)) {
                    $page = 1;
                }

                $perPage = 10;
                $result = \Pimcore\Google\Cse::search($this->getParam('q'), (($page - 1) * $perPage), null, [
                    'cx' => '002859715628130885299:baocppu9mii'
                ], $this->getParam('facet'));

                $paginator = \Zend_Paginator::factory($result);
                $paginator->setCurrentPageNumber($page);
                $paginator->setItemCountPerPage($perPage);

                $this->view->paginator = $paginator;
                $this->view->result = $result;
            } catch (\Exception $e) {
                Logger::err($e);
                echo $e->getMessage();
                exit;
            }
        }
    }
}
