#!/usr/bin/env php
<?php
// application.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ob_get_clean();

define('PIMCORE_CONSOLE', true);

require_once dirname(__FILE__) . '/../../../pimcore/cli/startup.php';

use Symfony\Component\Console\Application;

$autoloader = \Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('Wvision');

$includePaths = [
    get_include_path()
];

$includePaths[] = PIMCORE_PLUGINS_PATH . '/Wvision/lib';

set_include_path(implode(PATH_SEPARATOR, $includePaths));

$application = new Application();
$application->add(new \Wvision\Command\InstallCommand());
$application->add(new \Wvision\Command\InstallClassesCommand());
$application->run();