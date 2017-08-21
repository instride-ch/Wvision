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
            $file_name = pathinfo($src,PATHINFO_BASENAME);
            copy($src,$dst.$file_name);
        }
    }
}

if (!function_exists('deleteDir')) {
    /**
     * Delete entire Directory
     *
     * @param string $dirPath
     */
    function deleteDir($dirPath)
    {
        if (! is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }


}
