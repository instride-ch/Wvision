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

namespace WvisionBundle\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ObfuscateEmailExtension extends AbstractExtension
{
    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return array An array of filters
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter(
                'obfuscateEmail', [$this, 'parse'], [
                    'is_safe' => ['html']
                ]
            )
        ];
    }

    /**
     * Twig filter callback
     *
     * @param $email
     *
     * @return string
     */
    public function parse($email): string
    {
        return mb_encode_numericentity($email, [0, 0xffff, 0, 0xffff], 'utf-8');
    }
}
