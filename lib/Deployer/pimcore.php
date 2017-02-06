<?php

namespace Deployer;

task('pimcore:deployment:classes-rebuild', function () {
    run("{{bin/php}} {{release_path}}/pimcore/cli/console.php deployment:classes-rebuild");
});
