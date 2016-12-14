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

namespace Wvision\Model\Configuration\Listing;

use Pimcore;
use Wvision\Model;

/**
 * Class Dao
 * @package Wvision\Model\Configuration\Listing
 */
class Dao extends Pimcore\Model\Dao\PhpArrayTable
{
    /**
     * configure.
     */
    public function configure()
    {
        parent::configure();
        $this->setFile('wvision_configurations');
    }

    /**
     * Loads a list of Configurations for the specicifies parameters, returns an array of Configuration elements.
     *
     * @return array
     */
    public function load()
    {
        $routesData = $this->db->fetchAll($this->model->getFilter(), $this->model->getOrder());

        $routes = array();
        foreach ($routesData as $routeData) {
            $routes[] = Model\Configuration::getById($routeData['id']);
        }

        $this->model->setConfigurations($routes);

        return $routes;
    }

    /**
     * get total count.
     *
     * @return int
     */
    public function getTotalCount()
    {
        $data = $this->db->fetchAll($this->model->getFilter(), $this->model->getOrder());
        $amount = count($data);

        return $amount;
    }
}
