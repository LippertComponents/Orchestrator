<?php

namespace LCI\MODX\Orchestrator\Deploy;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface DeployInterface
{
    /**
     * Deploy constructor.
     * @param Command $application
     */
    public function __construct(Command $application);

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
