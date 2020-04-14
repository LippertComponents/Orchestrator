<?php

namespace LCI\MODX\Orchestrator\Console\Command;

use LCI\MODX\Console\Command\BaseCommand;
use LCI\MODX\Orchestrator\Orchestrator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UninstallPackages extends BaseCommand
{
    public $loadMODX = true;

    protected function configure()
    {
        $this
            ->setName('orchestrator:remove')
            ->setAliases(['orch-remove'])
            ->setDescription('Uninstall Package, this will run migrations down for related packages');

        $this->addArgument(
            'package',
            InputArgument::REQUIRED,
            'Enter a valid package name, like lci/stockpile. Multiple packages can separated by a comma.'
            )
            ->addOption(
            'type',
            't',
            InputOption::VALUE_OPTIONAL,
            'Server type passed to run migrations, default is master. Possible master, staging, dev and local',
            'master'
            );
    }

    /**
     * Runs the command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \LCI\Blend\Exception\MigratorException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $packages = explode(',', $input->getArgument('package'));
        $type = $input->getOption('type');

        foreach ($packages as $package) {
            $output->writeln('### Orchestrator::uninstallComposerPackage() for '.$package.' and type: '.$type.'  ###');
            Orchestrator::uninstallComposerPackage($package, $type);
        }

        $output->writeln($this->getRunStats());
		return 0;
    }
}
