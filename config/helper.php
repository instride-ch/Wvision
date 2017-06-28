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

if (!function_exists("recurse_copy")) {
    /**
     * Recursive copy entire Directory
     *
     * @param string $src
     * @param string $dst
     * @param boolean $overwrite
     */
    function recurse_copy($src, $dst, $overwrite = false)
    {

        if(!file_exists($dst)) {
            if (@mkdir($dst) === false) {
                throw new \RuntimeException('The directory '.$dst.' could not be created.');
            }
        }

        // Check if path is directory or file
        if (is_dir($src)) {
            $dir = opendir($src);

            while (false !== ($file = readdir($dir))) {
                if (($file != '.') && ($file != '..')) {
                    if (is_dir($src . '/' . $file)) {
                        recurse_copy($src . '/' . $file, $dst . '/' . $file);
                    } else {
                        if (is_file($dst . "/" . $file) && $overwrite) {
                            if ($overwrite) {
                                unlink($dst . "/" . $file);
                                copy($src . '/' . $file, $dst . '/' . $file);
                            }
                        } else {
                            copy($src . '/' . $file, $dst . '/' . $file);
                        }
                    }
                }
            }
            closedir($dir);
        } else {
            $info = pathinfo($src);
            $file_name =  basename($src,'.'.$info['extension']);
            copy($src,$dst.$file_name);
        }
    }
}

