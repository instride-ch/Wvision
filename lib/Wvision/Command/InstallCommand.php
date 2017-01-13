<?php

namespace Wvision\Command;

use Pimcore\Config;
use Pimcore\Console\AbstractCommand;
use Pimcore\ExtensionManager;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Wvision\Plugin\Install;

class InstallCommand extends AbstractCommand
{
    protected function configure()
    {
        $this
            ->setName('install')
            ->setDescription('Install Pimcore and W-Vision Plugin')
            ->addOption(
                'database', 'd',
                InputOption::VALUE_REQUIRED,
                'Database'
            )
            ->addOption(
                'username', 'u',
                InputOption::VALUE_REQUIRED,
                'User'
            )
            ->addOption(
                'password', 'p',
                InputOption::VALUE_REQUIRED,
                'Password'
            )
            ->addOption(
                'socket', 'socket',
                InputOption::VALUE_OPTIONAL,
                'Socket'
            )
            ->addOption(
                'host', 'host',
                InputOption::VALUE_OPTIONAL,
                'Host', 'localhost'
            )
            ->addOption(
                'port', 'port',
                InputOption::VALUE_OPTIONAL,
                'Port', 3306
            )
            ->addOption(
                'adapter', 'adapter',
                InputOption::VALUE_OPTIONAL,
                'Adapter', 'mysqli'
            )
            ->addOption(
                'admin-password', 'ap',
                InputOption::VALUE_OPTIONAL,
                'Admin Password', $this->generatePassword(12)
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->disableLogging();
        $conf = Config::getSystemConfig();

        $adminPassword = $input->getOption("admin-password");

        if(!$conf) {

            $helper = $this->getHelper('question');
            $question = new ConfirmationQuestion("You are going to install Pimcore, are you sure? (y/n)", true);

            if (!$helper->ask($input, $output, $question)) {
                return;
            }

            $database = $input->getOption('database');
            $password = $input->getOption('password');
            $username = $input->getOption('username');

            $socket = $input->getOption('socket');
            $host = $input->getOption('host');
            $port = $input->getOption('port');

            $adapter = $input->getOption('adapter');

            $dbConfig = [
                'username' => $username,
                'password' => $password,
                'dbname' => $database
            ];

            if (file_exists($socket)) {
                $dbConfig["unix_socket"] = $socket;
            } else if ($host && $port) {
                $dbConfig["host"] = $host;
                $dbConfig["port"] = $port;
            }

            $db = \Zend_Db::factory($adapter, $dbConfig);

            $db->getConnection();

            $result = $db->fetchRow('SHOW VARIABLES LIKE "character\_set\_database"');
            if (!in_array($result['Value'], ["utf8", "utf8mb4"])) {
                throw new \Exception("Database charset is not utf-8");
            }

            $setup = new \Pimcore\Model\Tool\Setup();

            $setup->config([
                "database" => [
                    "adapter" => "Mysqli",
                    "params" => $dbConfig
                ],
            ]);

            $setup->database();
            \Pimcore::initConfiguration();
            $setup->getDao()->contents();
        }

        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion("You are going to install W-Vision Plugin, are you sure? (y/n)", true);

        if (!$helper->ask($input, $output, $question)) {
            return;
        }

        ExtensionManager::enable('plugin', 'Wvision');

        $config = ExtensionManager::getPluginConfig('Wvision');
        $className = $config["plugin"]["pluginClassName"];

        $install = new Install();
        $install->install($adminPassword);

        if (!$className::isInstalled()) {
            throw new \Exception(sprintf("Installation error"));
        }

        $output->writeln(sprintf("<info>W-Vision Admin User created using following password: %s</info>", $adminPassword));
    }

    protected function generatePassword($length = 8) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-%&/()=?!';
        $count = mb_strlen($chars);

        for ($i = 0, $result = ''; $i < $length; $i++) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }

        return $result;
    }
}