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

namespace Wvision;

class Tool {
    /**
     * @static
     * @param \Zend_Controller_Response_Abstract $response
     * @return bool
     */
    public static function isInkyResponse(\Zend_Controller_Response_Abstract $response)
    {
        // check if response is html
        $headers = $response->getHeaders();

        foreach ($headers as $header) {
            if ($header["name"] == "Content-Type") {
                if (strpos($header["value"], "inky") === false) {
                    return false;
                }
            }
        }

        return true;
    }
}