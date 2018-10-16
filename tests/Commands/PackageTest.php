<?php

namespace LCI\MODX\Orchestrator\Tests\Command;

use LCI\MODX\Console\Console;
use LCI\MODX\Orchestrator\Console\Application;
use LCI\MODX\Orchestrator\Tests\Base;
use Symfony\Component\Console\Tester\CommandTester;

class PackageTest extends Base
{
    public function testInstallCommand()
    {
        $console = new Console();
        $console->registerPackageCommands('LCI\MODX\Orchestrator\Console\ActivePackageCommands');

        /** @var Application $application */
        $application = new Application($console);
        $application->setAutoExit(false);
        $application->loadCommands();

        $command = $application->find('orchestrator:install');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName()
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains('### Orchestrator has been installed/updated ###', $output);
    }

    /**
     * @depends testInstallCommand
     */
    public function testInstallMigrationCreatedNamespace()
    {
        $modx = Console::$modx;
        // Verify that the following got installed into MODX

        $orchestratorNamespace = $modx->getObject('modNamespace', 'orchestrator');

        $this->assertInstanceOf(
            '\modNamespace',
            $orchestratorNamespace,
            'The modNamespace orchestrator did not get created'
        );
    }

    /**
     * @depends testInstallCommand
     */
    public function testInstallMigrationCreatedMediaSource()
    {
        $modx = Console::$modx;
        $orchestratorMediaSource = $modx->getObject('modMediaSource', ['name' => 'orchestrator']);

        $this->assertInstanceOf(
            '\modMediaSource',
            $orchestratorMediaSource,
            'The modMediaSource orchestrator did not get created'
        );

    }

    /**
     * @depends testInstallCommand
     */
    public function testInstallMigrationCreatedSystemSetting()
    {
        $modx = Console::$modx;
        // orchestrator.vendor_path
        $orchestratorSystemSetting = $modx->getObject('modSystemSetting', ['key' => 'orchestrator.vendor_path']);

        $this->assertInstanceOf(
            '\modSystemSetting',
            $orchestratorSystemSetting,
            'The modSystemSetting orchestrator.vendor_path did not get created'
        );
    }

    /**
     * @depends testInstallCommand
     */
    public function testInstallMigrationCreatedPlugin()
    {
        $modx = Console::$modx;
        // orchestrator.vendor_path
        $orchestratorPlugin = $modx->getObject('modPlugin', ['name' => 'requireComposerAutoloader']);

        $this->assertInstanceOf(
            '\modPlugin',
            $orchestratorPlugin,
            'The modPlugin requireComposerAutoloader did not get created'
        );
    }
}