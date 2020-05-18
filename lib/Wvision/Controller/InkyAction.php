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

namespace Wvision\Controller;

use Pelago\Emogrifier;

class InkyAction extends Action
{
    /**
     * Init controller
     */
    public function init()
    {
        parent::init();

        $this->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
    }

    public function postDispatch()
    {
        parent::postDispatch();

        $this->forceRender();
        $layout = \Zend_Layout::getMvcInstance();
        $content = $this->getResponse()->getBody();

        if ($layout) {
            $layout->{$layout->getContentKey()} = $content;
            $content = $layout->render();
        }

        $this->getResponse()->clearBody();
        $this->getResponse()->setBody($this->dispatchLoopShutdown($content));

        $this->setParam("noViewRenderer", true);
        $this->disableLayout();
    }

    /**
     * shutdown.
     */
    public function dispatchLoopShutdown($body)
    {
        include_once("simple_html_dom.php");
        
        // Bugfix wysiwyg
        $body = preg_replace_callback('/editableConfigurations\.push\(\{(.*?)\}\);[?:\s+<\/]/', function ($hit) {
            return 'editableConfigurations.push({' . htmlspecialchars($hit[1]) . '});';
        }, $body);

        $inky = new \Hampe\Inky\Inky();
        $body = $inky->releaseTheKraken($body);
        
        // Bugfix wysiwyg
        $body = preg_replace_callback('/editableConfigurations\.push\(\{(.*?)\}\);[?:\s+<\/]/', function ($hit) {
            return 'editableConfigurations.push({' . html_entity_decode($hit[1]) . '});';
        }, $body);

        $html = str_get_html($body);

        if($html) {
            $styles = $html->find("link[rel=stylesheet], style[type=text/css]");
            $stylesheetContent = "";

            foreach ($styles as $style) {
                if ($style->tag == "style") {
                    $stylesheetContent .= $style->innertext;
                } else {
                    $source = $style->href;
                    $path = "";
                    if (is_file(PIMCORE_ASSET_DIRECTORY . $source)) {
                        $path = PIMCORE_ASSET_DIRECTORY . $source;
                    } else if (is_file(PIMCORE_DOCUMENT_ROOT . $source)) {
                        $path = PIMCORE_DOCUMENT_ROOT . $source;
                    }

                    if (!empty($path) && is_file("file://" . $path)) {
                        $content = file_get_contents($path);
                        $content = $this->correctReferences($source, $content);
                        if ($style->media && $style->media != "all") {
                            $content = "@media " . $style->media . " {" . $content . "}";
                        }
                        $stylesheetContent .= $content;
                        $style->outertext = "";
                    }
                }
            }

            if (strlen($stylesheetContent) > 1) {
                $head = $html->find("head", 0);
                $head->innertext = $head->innertext . "\n" . '<style>' . $stylesheetContent . '</style>' . "\n";
            }

            $body = $html->save();

            $emogrifier = new Emogrifier();
            $emogrifier->setHtml($body);
            $emogrifier->setCss($stylesheetContent);
            $emogrifier->disableStyleBlocksParsing();

            $body = $emogrifier->emogrify();

            $html->clear();
            unset($html);
        }

        return trim($body);
    }

    protected function correctReferences ($base, $content) {
        // check for url references
        preg_match_all('/url\((.*)\)/iU', $content, $matches);

        foreach ($matches[1] as $ref) {
            // do some corrections
            $ref = str_replace('"',"",$ref);
            $ref = str_replace(' ',"",$ref);
            $ref = str_replace("'","",$ref);
            $path = $this->correctUrl($ref, $base);
            //echo $ref . " - " . $path . " - " . $url . "<br />";
            $content = str_replace($ref,$path,$content);
        }
        // check for @import references
        preg_match_all('/\@import(.*);/iU', $content, $matches);

        foreach ($matches[1] as $ref) {
            // do some corrections
            $ref = str_replace('"',"",$ref);
            $ref = str_replace(' ',"",$ref);
            $ref = str_replace("'","",$ref);
            $path = $this->correctUrl($ref, $base);
            //echo $ref . " - " . $path . " - " . $url . "<br />";
            $content = str_replace($ref,$path,$content);
        }
        return $content;
    }

    protected function correctUrl ($rel, $base) {
        /* return if already absolute URL */
        if (parse_url($rel, PHP_URL_SCHEME) != '') return $rel;
        /* queries and anchors */
        if ($rel[0]=='#' || $rel[0]=='?') return $base.$rel;
        /* parse base URL and convert to local variables:
           $scheme, $host, $path */
        extract(parse_url($base));
        /* remove non-directory element from path */
        $path = preg_replace('#/[^/]*$#', '', $base);
        /* destroy path if relative url points to root */
        if ($rel[0] == '/') $path = '';
        /* dirty absolute URL */
        $abs = "$path/$rel";
        /* replace '//' or '/./' or '/foo/../' with '/' */
        $re = array('#(/\.?/)#', '#/(?!\.\.)[^/]+/\.\./#');
        for($n=1; $n>0; $abs=preg_replace($re, '/', $abs, -1, $n)) {}
        /* absolute URL is ready! */
        return $abs;
    }
}
