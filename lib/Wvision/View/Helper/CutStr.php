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

namespace Wvision\View\Helper;

class CutStr extends \Zend_View_Helper_Url
{
    /**
     * cut a string to a defined length
     *
     * @param  string $string string you want to cut
     * @param  int    $length length you want your string to be
     * @return string         cut string
     */
    public function cutStr($string, $length)
    {
        if ($length < strlen($string)) {
            $text = substr($string, 0, $length);

            if (false !== ($strrpos = strrpos($text, " "))) {
                $text = substr($text, 0, $strrpos);
            }

            $string = $text . ' ...';
        }

        return $string;
    }
}
