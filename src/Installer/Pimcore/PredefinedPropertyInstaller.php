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

namespace WvisionBundle\Installer\Pimcore;

use Pimcore\Model\Property\Predefined;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Yaml;
use WvisionBundle\Installer\Configuration\PredefinedPropertyConfiguration;
use WvisionBundle\Installer\ResourceInstallerInterface;

final class PredefinedPropertyInstaller implements ResourceInstallerInterface
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**<
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function installResources(OutputInterface $output)
    {
        $propertyFilesToInstall = [
            '@WvisionBundle/Resources/install/pimcore/predefined-properties.yml'
        ];

        $progress = new ProgressBar($output);
        $progress->setBarCharacter('<info>░</info>');
        $progress->setEmptyBarCharacter(' ');
        $progress->setProgressCharacter('<comment>░</comment>');
        $progress->setFormat(' %current%/%max% [%bar%] %percent:3s%% %message%');

        $processor = new Processor();
        $configurationDefinition = new PredefinedPropertyConfiguration();

        $propertiesToInstall = [];
        foreach ($propertyFilesToInstall as $file) {
            $file = $this->kernel->locateResource($file);

            if (file_exists($file)) {
                $properties = Yaml::parse(file_get_contents($file));
                $properties = $processor->processConfiguration($configurationDefinition, ['predefined-properties' => $properties]);
                $properties = $properties['properties'];

                foreach ($properties as $name => $propertyData) {
                    $propertiesToInstall[$name] = $propertyData;
                }
            }
        }

        $progress->start(\count($propertiesToInstall));

        foreach ($propertiesToInstall as $name => $propertyData) {
            $progress->setMessage(sprintf('<error>Install Predefined Property %s</error>', $name));

            $this->installProperty($name, $propertyData);

            $progress->advance();
        }

        $progress->finish();
    }

    /**
     * Check if predefined property is already installed
     *
     * @param $key
     * @param $properties
     * @return Predefined
     * @throws \Exception
     */
    private function installProperty($key, $properties): Predefined
    {
        $property = new Predefined();

        try {
            $property->getDao()->getByKey($key);
        } catch (\Exception $e) {
            // Predefined property does not exist, so we install it
            $property = Predefined::create();
            $property->setName($properties['name']);
            $property->setDescription($properties['description']);
            $property->setKey($key);
            $property->setType($properties['type']);
            $property->setData($properties['data']);
            $property->setConfig($properties['config']);
            $property->setCtype($properties['ctype']);
            $property->setInheritable($properties['inheritable']);
            $property->save();
        }

        return $property;
    }
}