<?php

namespace LCI\MODX\Orchestrator\Deploy;

use modX;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface DeployInterface
{
    /**
     * DeployInterface constructor.
     * @param Command $application
     * @param modX $modx
     */
    public function __construct(Command $application, modX $modx);

    /**
     * @param OutputInterface $output
     */
    public function describe(OutputInterface $output);

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function run(InputInterface $input, OutputInterface $output);
}
