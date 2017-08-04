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

namespace WvisionBundle\Deployer;

task('pimcore:deployment:classes-rebuild', function () {
    run('{{bin/php}} {{release_path}}/bin/console deployment:classes-rebuild');
});