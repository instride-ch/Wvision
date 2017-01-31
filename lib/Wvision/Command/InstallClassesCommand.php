<?php

namespace Wvision\Command;

use Pimcore\Config;
use Pimcore\Console\AbstractCommand;
use Pimcore\ExtensionManager;
use Pimcore\Model\Object\ClassDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Wvision\Plugin\Install;

class InstallClassesCommand extends AbstractCommand
{
    protected function configure()
    {
        $this
            ->setName('install-classes')
            ->setDescription('Install Classes');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->disableLogging();

        $objectClassesFolder = PIMCORE_CLASS_DIRECTORY ;
        $files = glob($objectClassesFolder . "/*.php");

        foreach ($files as $file) {
            $class = include $file;

            if($class instanceof ClassDefinition) {
                $existingClass = ClassDefinition::getByName($class->getName());

                if($existingClass instanceof ClassDefinition) {
                    $existingClass->save();
                }
                else {
                    $class->save();
                }
            }
        }
    }
}