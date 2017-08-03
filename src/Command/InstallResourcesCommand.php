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

namespace WvisionBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class InstallResourcesCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('wvision:resources:install')
            ->setDescription('Install Resources.')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command installs Resources. (Like Static Routes or Pimcore Assets)
EOT
            )
            ->addOption(
                'application-name', 'a',
                InputOption::VALUE_REQUIRED,
                'Application Name'
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $outputStyle = new SymfonyStyle($input, $output);
        $outputStyle->writeln(sprintf(
            'Install Resources for Environment <info>%s</info>.',
            $this->getContainer()->get('kernel')->getEnvironment()
        ));

        $this
            ->getContainer()->get('WvisionBundle\Installer\CompositeResourceInstaller')
            ->installResources($output, $input->getOption('application-name'));

        return 0;
    }
}