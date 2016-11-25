<?php
namespace Wvision;

use Pimcore\API\Plugin as PluginLib;

/**
 * Plugin
 */
class Plugin extends PluginLib\AbstractPlugin implements PluginLib\PluginInterface
{
    /**
     * Init Plugin
     */
    public function init()
    {
        parent::init();
    }

    /**
     * Install Plugin
     */
    public static function install()
    {
        // cp();
        // \Pimcore\Tool\Console::exec('npm install' . PIMCORE_DOCUMENT_ROOT);
        // \Pimcore\Tool\Console::execInBackground('npm install' . PIMCORE_DOCUMENT_ROOT . ' > log.log');
        return true;
    }

    /**
     * Uninstall Plugin
     */
    public static function uninstall()
    {
        return true;
    }

    /**
     * Plugin isInstalled
     */
    public static function isInstalled()
    {
        return true;
    }
}
