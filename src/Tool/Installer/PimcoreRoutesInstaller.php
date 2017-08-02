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

use Pimcore\Model\Staticroute;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Yaml;
use WvisionBundle\Tool\Installer\Configuration\RouteConfiguration;

final class PimcoreRoutesInstaller implements ResourceInstallerInterface
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
        $parameter = $applicationName ? sprintf('app.%s.install.routes', $applicationName) : 'wvision.install.routes';

        if ($this->kernel->getContainer()->hasParameter($parameter)) {
            $routeFilesToInstall = $this->kernel->getContainer()->getParameter($parameter);
            $routesToInstall = [];

            $progress = new ProgressBar($output);
            $progress->setBarCharacter('<info>░</info>');
            $progress->setEmptyBarCharacter(' ');
            $progress->setProgressCharacter('<comment>░</comment>');
            $progress->setFormat(' %current%/%max% [%bar%] %percent:3s%% %message%');

            $processor = new Processor();
            $configurationDefinition = new RouteConfiguration();

            foreach ($routeFilesToInstall as $file) {
                $file = $this->kernel->locateResource($file);

                if (file_exists($file)) {
                    $routes = Yaml::parse(file_get_contents($file));
                    $routes = $processor->processConfiguration($configurationDefinition, ['staticroutes' => $routes]);
                    $routes = $routes['routes'];

                    foreach ($routes as $name => $routeData) {
                        $routesToInstall[$name] = $routeData;
                    }
                }
            }

            $progress->start(count($routesToInstall));

            foreach ($routesToInstall as $name => $routeData) {
                $progress->setMessage(sprintf('<error>Install Route %s</error>', $name));

                $this->installRoute($name, $routeData);

                $progress->advance();
            }

            $progress->finish();
        }
    }

    /**
     * Check if route is already installed
     *
     * @param $name
     * @param $properties
     * @return Staticroute
     */
    private function installRoute($name, $properties)
    {
        $route = new Staticroute();

        try {
            $route->getDao()->getByName($name, null);
        } catch (\Exception $e) {
            // Route does not exist, so we install it
            $route = Staticroute::create();
            $route->setName($name);
            $route->setPattern($properties['pattern']);
            $route->setReverse($properties['reverse']);
            $route->setModule($properties['module']);
            $route->setController($properties['controller']);
            $route->setAction($properties['action']);
            $route->setVariables($properties['variables']);
            $route->setPriority($properties['priority']);
            $route->save();
        }

        return $route;
    }
}