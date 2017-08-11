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

namespace WvisionBundle\Installer\Pimcore;

use Pimcore\File;
use Symfony\Component\Console\Output\OutputInterface;
use WvisionBundle\Installer\ResourceInstallerInterface;

class RobotsTxtInstaller implements ResourceInstallerInterface
{
    /**
     * {@inheritdoc}
     */
    public function installResources(OutputInterface $output)
    {
        $robotsPath = PIMCORE_CONFIGURATION_DIRECTORY . '/robots.txt';
        $data = "User-agent: *\nDisallow: /";

        File::put($robotsPath, $data);
    }
}