<?php

namespace Deployer;

task('w-vision:setup', function() {
    run("{{bin/php}} {{release_path}}/plugins/Wvision/cli/console.php install -d {{database_name}} -u {{database_user}} -p '{{database_pwd}}' --admin-password '{{pimcore_admin_password}}' --admin-user '{{pimcore_admin_user}}'");
});

task('w-vision:install-classes', function() {
    run("{{bin/php}} {{release_path}}/plugins/Wvision/cli/console.php install-classes");
});

task('w-vision:minify-html', function() {
    run("npm gulp production {{release_path}}");
});