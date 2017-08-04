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

set('bin/npm', function() {
   if (commandExist('npm')) {
       return run('which npm')->toString();
   }

   return false;
});

task('npm:install', function() {
   run('cd {{release_path}} && {{env_vars}} {{bin/npm}} install-workflow');
});

task('npm:production', function() {
    run('cd {{release_path}} && {{env_vars}} {{bin/npm}} run build');
});