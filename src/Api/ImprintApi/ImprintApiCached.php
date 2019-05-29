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

use Pimcore\Cache\Core\CoreHandlerInterface;

class ImprintApiCached implements ImprintApiInterface
{
    /**
     * Cache key for all API calls.
     */
    private const CACHE_KEY = 'wvision_imprint';

    /**
     * @var CoreHandlerInterface
     */
    private $cacheHelper;

    /**
     * The default cache lifetime is one week.
     *
     * @var int
     */
    private $cacheLifetime = 604800;

    /**
     * @var ImprintApiInterface
     */
    private $decorated;

    /**
     * @param CoreHandlerInterface $cacheHelper
     * @param ImprintApiInterface $decorated
     */
    public function __construct(CoreHandlerInterface $cacheHelper, ImprintApiInterface $decorated)
    {
        $this->cacheHelper = $cacheHelper;
        $this->decorated = $decorated;
    }

    /**
     * {@inheritdoc}
     */
    public function getData(array $addresses = []): ?array
    {
        $cacheKey = $this->getCacheKey($addresses);

        if (!$result = $this->cacheHelper->load($cacheKey)) {
            $result = $this->decorated->getData($addresses);

            $this->cacheHelper->save(
                $cacheKey,
                $result,
                [static::CACHE_KEY],
                $this->cacheLifetime
            );
        }

        return $result;
    }

    /**
     * Assembles a unique key for caching
     *
     * @param array $addresses
     * @return string
     */
    private function getCacheKey(array $addresses = []): string
    {
        return empty($addresses)
            ? static::CACHE_KEY
            : sprintf('%s_%s', static::CACHE_KEY, implode('_', $addresses));
    }
}
