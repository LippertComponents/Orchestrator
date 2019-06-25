<?php

namespace LCI\MODX\Orchestrator\Console\Command;

use LCI\MODX\Console\Command\BaseCommand;
use LCI\MODX\Orchestrator\Deploy\Deploy;
use LCI\MODX\Orchestrator\Orchestrator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DeployCommand extends BaseCommand
{
    public $loadMODX = true;

    protected function configure()
    {
        $this
            ->setName('orchestrator:deploy')
            ->setAliases(['deploy'])
            ->setDescription('Run deploy script');

        // show the class used:
        $this
            ->addOption(
            'describe',
            'd',
            InputOption::VALUE_OPTIONAL,
            'Describe the deploy class used and the description of what it will do. 1/0',
            0
            );
        // @TODO list all packages in config
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
        $describe = (bool)$input->getOption('describe');

        $modx = $this->console->loadMODX();

        if (!empty($custom_class = getenv('LCI_MODX_ORCHESTRATOR_DEPLOY_EXTENDED_CLASS'))) {
            /** @var Deploy $orchestratorDeploy */
            $orchestratorDeploy = new $custom_class($modx);

        } else {
            /** @var Deploy $orchestratorDeploy */
            $orchestratorDeploy = new Deploy($this);
        }

        if ($describe) {
            $output->writeln('Class: '. get_class($orchestratorDeploy));
            $orchestratorDeploy->describe($output);

        } else {
            $orchestratorDeploy->run($input, $output);
        }

        $output->writeln($this->getRunStats());
    }
}
