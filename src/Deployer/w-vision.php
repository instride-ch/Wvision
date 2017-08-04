<?php

namespace WvisionBundle\Deployer;

// TODO: Is this command still needed?
task('w-vision:setup', function() {
    run("{{bin/php}} {{release_path}}/bin/console wvision:setup:install -d {{database_name}} -u {{database_user}} -p '{{database_pwd}}' --admin-password '{{pimcore_admin_password}}' --admin-user '{{pimcore_admin_user}}'");
});

// TODO: Is this command still needed?
task('w-vision:install-classes', function() {
    run('{{bin/php}} {{release_path}}/bin/console wvision:resources:install-classes');
});