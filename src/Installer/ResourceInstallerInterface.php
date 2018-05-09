<?php
/**
 * w-vision
 *
 * LICENSE
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that is distributed with this source code.
 *
 * @copyright  Copyright (c) 2018 w-vision AG (https://www.w-vision.ch)
 */

namespace WvisionBundle\Installer;

use Symfony\Component\Console\Output\OutputInterface;

interface ResourceInstallerInterface
{
    /**
     * @param OutputInterface $output
     */
    public function installResources(OutputInterface $output);
}