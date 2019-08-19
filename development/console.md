---
layout: default
title: Console Commands
nav_order: 3
has_children: false
parent: Development
---
# Console Commands

Console commands are a safe and easy way to create custom commands that allow you to add features to your project or package. 
Or commands can be set to a schedule and called via a cron job to do some task.

## Setup

If you are not familiar with Symfony Console, then read through the [Creating a Command](https://symfony.com/doc/current/console.html#creating-a-command) 
page for details specific that commands. The examples below will just some things that are unique to Orchestrator. 

In your project create a directory Console and then within that directory create Command. 

### Local project example
```
core/components/local/Console/ActivePackageCommands.php
core/components/local/Console/Command/ExampleCommand.php
```

### Package example

The only difference for local and package development is the base path. In local the base path is `core/components/local/` but
for a package it would be `src/`.

```
src/Console/ActivePackageCommands.php
core/components/local/Console/Command/ExampleCommand.php
```

### ActivePackageCommands Class

The `ActivePackageCommands` class has a job to simple tell Orchestrator what commands are available to the user based on the 
status of the MODX install. Below is an example of what this file would look like. Note you can name this class whatever you 
would like, it does not have to use the example name.

```php
<?php
namespace Local\MODX\Website\Console;

use LCI\MODX\Console\Application;
use LCI\MODX\Console\Command\PackageCommands;
use LCI\MODX\Console\Console;

class ActivePackageCommands implements PackageCommands
{
    /** @var Console  */
    protected $console;

    /** @var array  */
    protected $commands = [
        'modx_installed' => [
            'Local\MODX\Website\Console\Command\BuildCache',
        ],
        // If you wanted a command available when MODX is not installed you would define that here:
        'modx_not_installed' => []
    ];

    public function __construct(Console $console)
    {
        $this->console = $console;
    }

    /**
     * @return array ~ of Fully qualified names of all command class
     */
    public function getAllCommands()
    {
        $all_commands = [];
        foreach ($this->commands as $group => $commands) {
            foreach ($commands as $command) {
                $all_commands[] = $command;
            }
        }

        return $all_commands;
    }

    /**
     * @return array ~ of Fully qualified names of active command classes. This could differ from all if package creator
     *      has different commands based on the state like the DB. Example has Install and Uninstall, only one would
     *      be active/available depending on the state
     */
    public function getActiveCommands()
    {
        $active_commands = [];

        if ($this->console->isModxInstalled()) {

            $commands = $this->commands['modx_installed'];
            foreach ($commands as $command) {
                $active_commands[] = $command;
            }

        } else {
            $commands = $this->commands['modx_not_installed'];
            foreach ($commands as $command) {
                $active_commands[] = $command;
            }
        }

        return $active_commands;
    }

    /**
     * @param \LCI\MODX\Console\Application $application
     * @return \LCI\MODX\Console\Application
     */
    public function loadActiveCommands(Application $application)
    {
        $commands = $this->getActiveCommands();

        foreach ($commands as $command) {
            $class = new $command();

            if (is_object($class) ) {
                if (method_exists($class, 'setConsole')) {
                    $class->setConsole($this->console);
                }

                $application->add($class);
            }
        }

        return $application;
    }
}
```

### ExampleCommand Class

```php
<?php
namespace Local\MODX\Website\Console\Command;

use LCI\MODX\Console\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ExampleCommand extends BaseCommand
{
    public $loadMODX = true;

    protected function configure()
    {
        $this
            ->setName('website:example')
            ->setDescription('This is an example command')
            ->addOption(
                'ids',
                'i',
                InputOption::VALUE_OPTIONAL,
                'Pass a valid list of comma separated Resource IDs',
                '0'
            );
    }

    /**
     * Runs the command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ids = $input->getOption('ids');

        // Get MODX:
        $modx = $this->console->loadMODX();

        $output->writeln('You provided the following Resource IDs: '.$ids);

        $output->writeln($this->getRunStats());
    }
}
```

## Migration

Once you have created your commands you will need to create a migration to register you commands to Orchestrator.

```php
<?php

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // now register the commands:
        $console = new \LCI\MODX\Console\Console();
        $console->registerPackageCommands('Local\MODX\Website\Console\ActivePackageCommands');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        // now register the commands:
        $console = new \LCI\MODX\Console\Console();
        $console->cancelRegistrationPackageCommands('Local\MODX\Website\Console\ActivePackageCommands');
    }
```

Once the migration has been ran then your class will be noted it the `core/config/lci_console_package_commands.php` file. 
You can manually update that file if needed and commit this file to git.
