<?php

namespace Deployer;

task('coreshop:install', function () {
    run("{{bin/php}} {{release_path}}/pimcore/cli/console.php coreshop:install");
});

task('coreshop:install-sql', function () {
    run("{{bin/php}} {{release_path}}/pimcore/cli/console.php coreshop:install --sql-only");
});