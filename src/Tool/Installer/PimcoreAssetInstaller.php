<?php
/**
 * w-vision
 *
 * LICENSE
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that is distributed with this source code.
 *
 * @copyright  Copyright (c) 2017 Woche-Pass AG (https://www.w-vision.ch)
 */

namespace WvisionBundle\Tool\Installer;

use Pimcore\Model\Asset;
use Pimcore\Tool;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Yaml;
use WvisionBundle\Tool\Installer\Configuration\AssetConfiguration;

final class PimcoreAssetInstaller implements ResourceInstallerInterface
{
    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**<
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * {@inheritdoc}
     */
    public function installResources(OutputInterface $output, $applicationName = null)
    {
        $parameter = $applicationName ? sprintf('app.%s.install.assets', $applicationName) : 'wvision.install.assets';

        if ($this->kernel->getContainer()->hasParameter($parameter)) {
            $assetFilesToInstall = $this->kernel->getContainer()->getParameter($parameter);
            $assetsToInstall = [];

            $progress = new ProgressBar($output);
            $progress->setBarCharacter('<info>░</info>');
            $progress->setEmptyBarCharacter(' ');
            $progress->setProgressCharacter('<comment>░</comment>');
            $progress->setFormat(' %current%/%max% [%bar%] %percent:3s%% %message%');

            $processor = new Processor();
            $configurationDefinition = new AssetConfiguration();

            foreach ($assetFilesToInstall as $file) {
                $file = $this->kernel->locateResource($file);

                if (file_exists($file)) {
                    $assets = Yaml::parse(file_get_contents($file));
                    $assets = $processor->processConfiguration($configurationDefinition, ['assets' => $assets]);
                    $assets = $assets['assets'];

                    foreach ($assets as $assetData) {
                        $assetsToInstall[] = $assetData;
                    }
                }
            }

            $progress->start(count($assetsToInstall));

            foreach ($assetsToInstall as $assetData) {
                $progress->setMessage(sprintf('<error>Install Asset %s/%s</error>', $assetData['path'], $assetData['filename']));

                $this->installAsset($assetData);

                $progress->advance();
            }

            $progress->finish();
        }
    }

    /**
     * @param $properties
     * @return Asset|null
     */
    private function installAsset($properties)
    {
        $path = '/' . $properties['path'] . '/' . $properties['filename'];

        if (!Asset\Service::pathExists($path)) {
            $class = "Pimcore\\Model\\Asset\\" . ucfirst($properties['type']);

            if (Tool::classExists($class)) {
                /** @var Asset $asset */
                $asset = new $class();
                $asset->setParent(Asset::getByPath('/' . $properties['path']));
                $asset->setFilename($properties['filename']);

                if (file_exists(PIMCORE_ASSET_DIRECTORY . $path)) {
                    $asset->setData(file_get_contents(PIMCORE_ASSET_DIRECTORY . $path ));
                }

                $asset->save();

                return $asset;
            }
        }

        return null;
    }
}