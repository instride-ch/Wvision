<?php

namespace Wvision;

use Pimcore\API\Plugin as PluginLib;

class Plugin extends PluginLib\AbstractPlugin implements PluginLib\PluginInterface
{
    public function init()
    {
        parent::init();
    }

    public static function install()
    {
        // cp();
        // \Pimcore\Tool\Console::execInBackground('npm install ' . PIMCORE_DOCUMENT_ROOT . ' > log.log');
        return true;
    }

    public static function uninstall()
    {
        return true;
    }

    public static function isInstalled()
    {
        return true;
    }
}
