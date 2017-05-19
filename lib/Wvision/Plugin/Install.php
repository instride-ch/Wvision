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

namespace Wvision\Plugin;

use Pimcore\API\Plugin as PluginLib;
use Pimcore\Model\Document;
use Pimcore\Config;
use Pimcore\Model;
use Pimcore\File;
use Pimcore\Tool;

class Install
{
    /**
     * Installs everything needed to start the dev environment.
     */
    public function install()
    {
        $this->installSystemSettings();
        $this->installRobotsTxt();
        $this->installUserImage();
        $this->installDocuments('documents');
        $this->installAssets('assets');
        $this->installGulpFiles();
    }

    /**
     * Recursively copies all gulp files into root directory.
     */
    protected function installGulpFiles()
    {
        recurse_copy(PIMCORE_PLUGINS_PATH . '/Wvision/install/gulp', PIMCORE_DOCUMENT_ROOT . '/');
    }

    /**
     * Recursively copies the default w-vision user image into the user image directory.
     */
    protected function installUserImage()
    {
        recurse_copy(PIMCORE_PLUGINS_PATH . '/Wvision/install/user-2.png', PIMCORE_USERIMAGE_DIRECTORY . '/');
    }

    /**
     * Installs the default system settings.
     */
    protected function installSystemSettings()
    {
        $defaultConfig = PIMCORE_PLUGINS_PATH . '/Wvision/install/system-settings.php';
        $systemConfigFile = Config::locateConfigFile('system.php');

        if (file_exists($defaultConfig) && file_exists($systemConfigFile)) {
            $defaultConfig = new \Zend_Config(include $defaultConfig, true);
            $config = new \Zend_Config(include($systemConfigFile), true);

            $config->merge($defaultConfig);

            File::putPhpFile($systemConfigFile, to_php_data_file_format($config->toArray()));
        }
    }

    /**
     * Recursively copies the robots.txt into the config directory.
     */
    protected function installRobotsTxt()
    {
        recurse_copy(PIMCORE_PLUGINS_PATH . '/Wvision/install/robots.txt', PIMCORE_CONFIGURATION_DIRECTORY . '/');
    }

    /**
     * Creates some assets with data based from XML file.
     *
     * @param $xml
     */
    protected function installAssets($xml)
    {
        $dataPath = PIMCORE_PLUGINS_PATH . '/Wvision/install/data/assets';
        $file = $dataPath . "/$xml.xml";

        if (file_exists($file)) {
            $config = new \Zend_Config_Xml($file);
            $config = $config->toArray();

            if (array_key_exists('assets', $config)) {

                foreach ($config['assets'] as $value) {
                    foreach ($value as $asset) {
                        $assetObject = Model\Asset::getByPath('/' . $asset['path'] . '/' . $asset['name']);

                        if (!$assetObject instanceof Model\Asset) {
                            $assetObject = new Model\Asset();
                            $assetObject->setFilename(File::getValidFilename($asset['name']));
                            $assetObject->setParent(Model\Asset\Service::createFolderByPath($asset['path']));
                            $assetObject->setData(file_get_contents($dataPath . '/files/' . $asset['file']));
                            $assetObject->save();
                        }
                    }
                }
            }
        }
    }

    /**
     * Creates some documents with data based from XML file.
     *
     * @param $xml
     * @throws \Exception
     */
    protected function installDocuments($xml)
    {
        $dataPath = PIMCORE_PLUGINS_PATH . '/Wvision/install/data/documents';
        $file = $dataPath . "/$xml.xml";

        if (file_exists($file)) {
            $config = new \Zend_Config_Xml($file);
            $config = $config->toArray();

            if (array_key_exists('documents', $config)) {
                $validLanguages = explode(',', \Pimcore\Config::getSystemConfig()->general->validLanguages);
                $languagesDone = [];

                foreach ($validLanguages as $language) {
                    $locale = new \Zend_Locale($language);
                    $language = $locale->getLanguage();
                    $languageDocument = Document::getByPath('/'.$language);

                    if (!$languageDocument instanceof Document) {
                        $languageDocument = new Document\Page();
                        $languageDocument->setParent(Document::getById(1));
                        $languageDocument->setKey($language);
                        $languageDocument->save();
                    }

                    foreach ($config['documents'] as $value) {
                        foreach ($value as $doc) {
                            $document = Document::getByPath('/' . $language . '/' . $doc['path'] . '/' . $doc['key']);

                            if (!$document) {
                                $class = 'Pimcore\\Model\\Document\\' . ucfirst($doc['type']);

                                if (Tool::classExists($class)) {
                                    $document = new $class();
                                    $document->setParent(Document::getByPath('/' . $language . '/' . $doc['path']));
                                    $document->setKey($doc['key']);
                                    $document->setProperty('language', $language, 'text', true);

                                    if ($document instanceof Document\PageSnippet) {
                                        if (array_key_exists('action', $doc)) {
                                            $document->setAction($doc['action']);
                                        }

                                        if (array_key_exists('controller', $doc)) {
                                            $document->setController($doc['controller']);
                                        }

                                        if (array_key_exists('module', $doc)) {
                                            $document->setModule($doc['module']);
                                        }
                                    }

                                    $document->setProperty('language', 'text', $language);
                                    $document->save();

                                    if (array_key_exists('content', $doc)) {
                                        foreach ($doc['content'] as $fieldLanguage => $fields) {
                                            if ($fieldLanguage !== $language) {
                                                continue;
                                            }

                                            foreach ($fields['field'] as $field) {
                                                $key = $field['key'];
                                                $type = $field['type'];
                                                $content = null;

                                                if (array_key_exists('file', $field)) {
                                                    $file = $dataPath.'/'.$field['file'];

                                                    if (file_exists($file)) {
                                                        $content = file_get_contents($file);
                                                    }
                                                }

                                                if (array_key_exists('value', $field)) {
                                                    $content = $field['value'];
                                                }

                                                if ($content) {
                                                    if ($type === 'objectProperty') {
                                                        $document->setValue($key, $content);
                                                    } else {
                                                        $document->setRawElement($key, $type, $content);
                                                    }
                                                }
                                            }
                                        }

                                        $document->save();
                                    }
                                }
                            }

                            //Link translations
                            foreach ($languagesDone as $doneLanguage) {
                                $translatedDocument = Document::getByPath('/' . $doneLanguage . '/' . $doc['path'] . '/' . $doc['key']);

                                if ($translatedDocument) {
                                    $service = new \Pimcore\Model\Document\Service();

                                    $service->addTranslation($document, $translatedDocument, $doneLanguage);
                                }
                            }
                        }
                    }

                    $languagesDone[] = $language;
                }
            }
        }
    }

    /**
     * Creates a new w-vision admin user.
     *
     * @param $password
     */
    public function createUser($username, $password)
    {
        $settings = [
            'username' => $username,
            'password' => $password
        ];

        if ($user = Model\User::getByName($settings['username'])) {
            $user->delete();
        }

        $user = Model\User::create([
            'parentId' => 0,
            'username' => $settings['username'],
            'password' => \Pimcore\Tool\Authentication::getPasswordHash($settings['username'], $settings['password']),
            'active' => true
        ]);
        $user->setAdmin(true);
        $user->save();
    }
}