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

namespace Wvision\Model\Configuration;

use Wvision\Model\Configuration;
use Pimcore\Model;

/**
 * Class Listing
 * @package Wvision\Model\Configuration
 */
class Listing extends Model\Listing\JsonListing
{
    /**
     * Contains the results of the list. They are all an instance of Configuration.
     *
     * @var array
     */
    public $configurations = null;

    /**
     * Get Configurations.
     *
     * @return Configuration[]
     */
    public function getConfigurations()
    {
        if (is_null($this->configurations)) {
            $this->load();
        }

        return $this->configurations;
    }

    /**
     * Set Configuration.
     *
     * @param array $configurations
     */
    public function setConfigurations($configurations)
    {
        $this->configurations = $configurations;
    }
}
