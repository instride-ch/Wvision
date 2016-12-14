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

namespace Wvision\Tool;

use Wvision\Model\Configuration;
use Pimcore\Translate;

class Notification extends \Zend_View_Helper_Placeholder
{
    /**
     * displays a success message to the user
     *
     * @param  string $title title of message
     * @param  string $text  text of message
     * @return string        javascript command
     */
    public static function success($title = null, $text = null)
    {
        if ($title && $text) {
            $cmd = 'toastr.success("' . $text . '", "' . $title . '");';

            self::sendToView($cmd);
        } else {
            throw new \Exception('Both title and text must be given');
        }
    }

    /**
     * displays a info message to the user
     *
     * @param  string $title title of message
     * @param  string $text  text of message
     * @return string        javascript command
     */
    public static function info($title = null, $text = null)
    {
        if ($title && $text) {
            $cmd = 'toastr.info("' . $text . '", "' . $title . '");';

            self::sendToView($cmd);
        } else {
            throw new \Exception('Both title and text must be given');
        }
    }

    /**
     * displays a warning message to the user
     *
     * @param  string $title title of message
     * @param  string $text  text of message
     * @return string        javascript command
     */
    public static function warning($title = null, $text = null)
    {
        if ($title && $text) {
            $cmd = 'toastr.warning("' . $text . '", "' . $title . '");';

            self::sendToView($cmd);
        } else {
            throw new \Exception('Both title and text must be given');
        }
    }

    /**
     * displays an error message to the user
     *
     * @param  string $title title of message
     * @param  string $text  text of message
     * @return string        javascript command
     */
    public static function error($title = null, $text = null)
    {
        if ($title && $text) {
            $cmd = 'toastr.error("' . $text . '", "' . $title . '");';

            self::sendToView($cmd);
        } else {
            throw new \Exception('Both title and text must be given');
        }
    }

    /**
     * sends retrieved content to view
     *
     * @param  string $content toastr cmd
     * @return \Zend_View_Helper_Placeholder
     */
    public static function sendToView($content)
    {
        $placeholder = new \Zend_View_Helper_Placeholder();
        $config = self::getConfig();

        $placeholder->placeholder('footer')->captureStart();
            echo '<script type="text/javascript">';
            echo     '$(document).ready(function(){';
            echo         $config;
            echo         $content;
            echo     '});';
            echo '</script>';
        $placeholder->placeholder('footer')->captureEnd();
    }

    /**
     * get all configs
     *
     * @return string toastr.options
     */
    public static function getConfig()
    {
        $configs = new Configuration\Listing();
        $configs->getConfigurations();

        $configArray = [];
        foreach ($configs->configurations as $key => $value) {
            if (strpos($value->key, 'APPLICATION.NOTIFICATION') !== false) {
                if ($value->data === false) {
                    $configArray[$value->key] = 'false';
                } elseif ($value->data === true) {
                    $configArray[$value->key] = 'true';
                } else {
                    $configArray[$value->key] = $value->data;
                }
            }
        }

        $config  = 'toastr.options = {';
		$config .=     '"closeButton":' . $configArray['APPLICATION.NOTIFICATION.CLOSE_BUTTON'] . ',';
		$config .= 	   '"debug":' . $configArray['APPLICATION.NOTIFICATION.DEBUG'] . ',';
		$config .=     '"newestOnTop":' . $configArray['APPLICATION.NOTIFICATION.NEWEST_ON_TOP'] . ',';
		$config .=     '"progressBar":' . $configArray['APPLICATION.NOTIFICATION.PROGRESSBAR'] . ',';
		$config .=     '"positionClass":"' . $configArray['APPLICATION.NOTIFICATION.POSITION'] . '",';
		$config .=     '"preventDuplicates":' . $configArray['APPLICATION.NOTIFICATION.PREVENT_DUPLICATES'] . ',';
		$config .=     '"onclick":null,';
		$config .=     '"showDuration":' . $configArray['APPLICATION.NOTIFICATION.SHOW_DURATION'] . ',';
		$config .=     '"hideDuration":' . $configArray['APPLICATION.NOTIFICATION.HIDE_DURATION'] . ',';
		$config .=     '"timeOut":' . $configArray['APPLICATION.NOTIFICATION.TIMEOUT'] . ',';
		$config .=     '"extendedTimeOut":' . $configArray['APPLICATION.NOTIFICATION.EXTENDED_TIMEOUT'] . ',';
		$config .=     '"showEasing":"' . $configArray['APPLICATION.NOTIFICATION.SHOW_EASING'] . '",';
		$config .=     '"hideEasing":"' . $configArray['APPLICATION.NOTIFICATION.HIDE_EASING'] . '",';
		$config .=     '"showMethod":"' . $configArray['APPLICATION.NOTIFICATION.SHOW_METHOD'] . '",';
		$config .=     '"hideMethod":"' . $configArray['APPLICATION.NOTIFICATION.HIDE_METHOD'] . '"';
		$config .= '};';

        return $config;
    }
}
