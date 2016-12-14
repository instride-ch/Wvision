<?php
/**
 * w-vision
 *
 * LICENSE
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that is distributed with this source code.
 *
 * @copyright  Copyright (c) 2015-2016 Woche-Pass AG (http://www.w-vision.ch)
 */

namespace Website\Views\Helper;

class HideEmail extends \Zend_View_Helper_Url
{
    /**
     * encrypt an email address
     *
     * @param  string $email email address you want to encrypt
     * @return string        encrypted email address
     */
    public function hideEmail($email)
    {
        return mb_encode_numericentity($email, [0,0xffff,0,0xffff], "utf-8");
    }
}
