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

namespace WvisionBundle\Twig\Extension;

class UrlLocaleExtension extends \Twig_Extension
{
    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return array An array of filters
     */
    public function getFilters()
    {
        return [
            new \Twig_Filter(
                'urlLocale', [$this, 'getUrlLocale'], [
                    'is_safe' => ['html']
                ]
            )
        ];
    }

    /**
     * Return a pretty locale for the url.
     *
     * @param $locale
     *
     * @return string The modified locale
     */
    public function getUrlLocale($locale)
    {
        if (strlen($locale) > 2 || strpos($locale, '_') !== false) {
            return strtolower(str_replace('_', '-', $locale));
        }

        return $locale;
    }
}