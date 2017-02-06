<?php

namespace Deployer;

set('bin/npm', function () {
    if (commandExist('npm')) {
        return run('which npm')->toString();
    }

    return false;
});

task('npm:install', function() {
    run('cd {{release_path}} && {{env_vars}} {{bin/npm}} install');
});

task('npm:production', function() {
    run('cd {{release_path}} && {{env_vars}} {{bin/npm}} start production');
});