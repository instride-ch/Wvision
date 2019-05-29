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

namespace WvisionBundle\Api\ImprintApi;

use GuzzleHttp\Exception\GuzzleException;

interface ImprintApiInterface
{
    /**
     * Returns the default imprint address and any other additionally provided addresses.
     *
     * @param array $addresses
     *
     * @return array|null
     *
     * @throws GuzzleException
     */
    public function getData(array $addresses = []): ?array;
}
