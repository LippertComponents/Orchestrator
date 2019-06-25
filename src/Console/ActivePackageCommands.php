<?php

namespace LCI\MODX\Orchestrator\Console;

use LCI\MODX\Console\Application;
use LCI\MODX\Console\Command\PackageCommands;
use LCI\MODX\Console\Console;

class ActivePackageCommands implements PackageCommands
{
    /** @var Console  */
    protected $console;

    /** @var array  */
    protected $commands = [
        'orchestrator_installed' => [
            'LCI\MODX\Orchestrator\Console\Command\DeployCommand',
            'LCI\MODX\Orchestrator\Console\Command\InstallPackages',
            'LCI\MODX\Orchestrator\Console\Command\UninstallPackages'
        ],
        'orchestrator_not_installed' => [
            'LCI\MODX\Orchestrator\Console\Command\Package'
        ]
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

            if ($this->isOrchestratorInstalled() && !$this->isOrchestratorRequireUpdate()) {
                $commands = $this->commands['orchestrator_installed'];
                foreach ($commands as $command) {
                    $active_commands[] = $command;
                }
            } else {
                $commands = $this->commands['orchestrator_not_installed'];
                foreach ($commands as $command) {
                    $active_commands[] = $command;
                }
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

    /**
     * @return bool
     */
    public function isOrchestratorInstalled()
    {
        $modx = $this->console->loadMODX();
        if (!empty($modx->getOption('orchestrator.vendor_path'))) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isOrchestratorRequireUpdate()
    {
        return false;
    }
}
