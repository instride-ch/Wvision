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

namespace WvisionBundle\Registry;

interface PrioritizedServiceRegistryInterface
{
    /**
     * @return array
     */
    public function all();

    /**
     * @param string $identifier
     * @param int    $priority
     * @param object $service
     * @throws ExistingServiceException
     * @throws \InvalidArgumentException
     */
    public function register($identifier, $priority, $service);

    /**
     * @param string $identifier
     * @throws NonExistingServiceException
     */
    public function unregister($identifier);

    /**
     * @param string $identifier
     * @return bool
     */
    public function has($identifier);

    /**
     * @param string $identifier
     * @return object
     * @throws NonExistingServiceException
     */
    public function get($identifier);

    /**
     * Get previous item to $identifier.
     *
     * @param $identifier
     * @return mixed
     */
    public function getPreviousTo($identifier);

    /**
     * Get all previous items to $identifier.
     *
     * @param $identifier
     * @return array
     */
    public function getAllPreviousTo($identifier);

    /**
     * Get previous item to $identifier.
     *
     * @param $identifier
     * @return mixed
     */
    public function getNextTo($identifier);

    /**
     * Get index for $identifier.
     *
     * @param $identifier
     * @return int
     */
    public function getIndex($identifier);
}