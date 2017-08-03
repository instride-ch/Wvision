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

class ExistingServiceException extends \InvalidArgumentException
{
    public function __construct($context, $type)
    {
        parent::__construct(sprintf('%s of type "%s" already exists.', $context, $type));
    }
}