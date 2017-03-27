<?php
/**
 * w-vision
 *
 * LICENSE
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that is distributed with this source code.
 *
 * @copyright  Copyright (c) 2016 Woche-Pass AG (http://www.w-vision.ch)
 */

namespace Wvision;

use Pimcore\API\Plugin as PluginLib;
use Pimcore\Config;
use Wvision\Controller\Plugin\Inky;
use Wvision\Model\Configuration;
use Wvision\Plugin\Install;

class Plugin extends PluginLib\AbstractPlugin implements PluginLib\PluginInterface
{
    /**
     * init plugin
     *
     * @return mixed init
     */
    public function init()
    {
        parent::init();

        require_once(PIMCORE_PLUGINS_PATH . "/Wvision/config/helper.php");

        \Pimcore::getEventManager()->attach('system.startup', function (\Zend_EventManager_Event $e) {
            $frontController = $e->getTarget();
        });

    }

    /**
     * install plugin
     *
     * @return bool true
     */
    public static function install()
    {
        $install = new Install();
        $install->install();

        Configuration::set("INSTALLED", true);
        Configuration::set("INSTALLED_VERSION", Version::getBuild());

        // cp();
        // \Pimcore\Tool\Console::exec('npm install' . PIMCORE_DOCUMENT_ROOT);
        // \Pimcore\Tool\Console::execInBackground('npm install' . PIMCORE_DOCUMENT_ROOT . ' > log.log');
        return true;
    }

    /**
     * uninstall plugin
     *
     * @return bool true
     */
    public static function uninstall()
    {
        return true;
    }

    /**
     * plugin isInstalled
     *
     * @return bool true
     */
    public static function isInstalled()
    {
        $versionInstalled = Configuration::get("INSTALLED_VERSION");

        if(!$versionInstalled || $versionInstalled < Version::getBuild()) {
            return false;
        }

        return true;
    }

    /**
     * get translation directory
     *
     * @return string
     */
    public static function getTranslationFileDirectory()
    {
        return PIMCORE_PLUGINS_PATH . '/Wvision/static/texts';
    }

    /**
     * get translation file
     *
     * @param string $language
     * @return string path to the translation file relative to plugin directory
     */
    public static function getTranslationFile($language)
    {
        if (is_file(self::getTranslationFileDirectory() . "/$language.csv")) {
            return "/Wvision/static/texts/$language.csv";
        } else {
            return '/Wvision/static/texts/en.csv';
        }
    }

    /**
     * get translate
     *
     * @param $lang
     * @return \Zend_Translate
     */
    public static function getTranslate($lang = null)
    {
        if (self::$_translate instanceof \Zend_Translate) {
            return self::$_translate;
        }
        if (is_null($lang)) {
            try {
                $lang = \Zend_Registry::get('Zend_Locale')->getLanguage();
            } catch (\Exception $e) {
                $lang = 'en';
            }
        }

        self::$_translate = new \Zend_Translate(
            'csv',
            PIMCORE_PLUGINS_PATH . self::getTranslationFile($lang),
            $lang,
            ['delimiter' => ',']
        );

        return self::$_translate;
    }
}
