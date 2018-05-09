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

class ObfuscateEmailExtension extends \Twig_Extension
{
    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return array An array of filters
     */
    public function getFilters(): array
    {
        return [
            new \Twig_Filter(
                'obfuscateEmail', [$this, 'parse'], [
                    'is_safe' => ['html']
                ]
            )
        ];
    }

    /**
     * Twig filter callback.
     * TODO: Implement a better email obfuscator
     * @link https://github.com/Propaganistas/Email-Obfuscator
     *
     * @param $email
     * @return string
     */
    public function parse($email): string
    {
        return mb_encode_numericentity($email, [0, 0xffff, 0, 0xffff], 'utf-8');
    }
}