<?php

namespace LCI\MODX\Orchestrator\Console\Command;

use LCI\MODX\Console\Command\BaseCommand;
use LCI\MODX\Orchestrator\Orchestrator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Package extends BaseCommand
{
    public $loadMODX = true;

    protected function configure()
    {
        $this
            ->setName('orchestrator:install')
            ->setDescription('Install/Update orchestrator');
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
        Orchestrator::install();

        $output->writeln('### Orchestrator has been installed/updated ###');
    }
}
