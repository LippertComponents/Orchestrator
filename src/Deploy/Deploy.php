<?php

namespace LCI\MODX\Orchestrator\Deploy;

use LCI\Blend\Exception\TransportException;
use LCI\Blend\Exception\TransportNotFoundException;
use LCI\Blend\Transport\MODXPackages;
use LCI\Blend\Transport\MODXPackagesConfig;
use LCI\MODX\Console\Console;
use LCI\MODX\Console\Helpers\ConsoleUserInteractionHandler;
use LCI\MODX\Console\Helpers\UserInteractionHandler;
use LCI\MODX\Orchestrator\Orchestrator;
use modX;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class Deploy implements DeployInterface
{
    /** @var Command */
    protected $command;

    /** @var modX  */
    protected $modx;

    /**
     * Deploy constructor.
     * @param Command $application
     * @param modX $modx
     */
    public function __construct(Command $application, modX $modx)
    {
        $this->command = $application;
        $this->modx = $modx;
    }

    /**
     * @param OutputInterface $output
     */
    public function describe(OutputInterface $output)
    {
        $output->writeln('1. You will be prompted with a question if you would like to clear the MODX cache before running migrations');
        $output->writeln('2. Require/update any MODX Extras as defined in the core/config/lci_modx_transport_package.php');
        $output->writeln('3. Run all Blend migrations that are ready for all orchestrator packages');
        $output->writeln('4. Run all Blend migrations that are ready for your local project');
        $output->writeln('5. You will be prompted with a question if you would like to clear the MODX cache after the migrations have been completed.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \LCI\Blend\Exception\MigratorException
     */
    public function run(InputInterface $input, OutputInterface $output)
    {
        $this->runBefore($input, $output);

        // clear all cache Y/N?
        $helper = $this->command->getHelper('question');
        $question = new ConfirmationQuestion('Clear MODX cache before running migrations?', true);

        if ($helper->ask($input, $output, $question)) {
            $output->writeln($this->clearMODXCache($input, $output));
        }

        $output->writeln(PHP_EOL.'### Orchestrator require/update MODX Extras as defined in: core/config/lci_modx_transport_package.php');
        $this->runRequireUpdateMODXPackages($input, $output);

        // run all package migrations
        $output->writeln(PHP_EOL.'### Orchestrator package Blend migrations');
        Orchestrator::updateAllOrchestratorComposerPackages();

        $output->writeln(PHP_EOL.'### Local Blend migrations');
        // run local blend migrations
        $this->runLocalBlendMigration($input, $output);

        $helper = $this->command->getHelper('question');
        $question = new ConfirmationQuestion('Migrations have been ran, clear MODX cache again?', true);

        if ($helper->ask($input, $output, $question)) {
            $output->writeln($this->clearMODXCache($input, $output));
        }

        $this->runAfter($input, $output);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Exception
     */
    protected function clearMODXCache(InputInterface $input, OutputInterface $output)
    {
        $command = $this->command->getApplication()->find('console:clear');

        $arguments = [
            'command' => 'console:clear'
        ];

        /** @var  $greetInput */
        $greetInput = new ArrayInput($arguments);

        return $command->run($greetInput, $output);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Exception
     */
    protected function runLocalBlendMigration(InputInterface $input, OutputInterface $output)
    {
        $command = $this->command->getApplication()->find('blend:migrate');

        $arguments = [
            'command' => 'blend:migrate'
        ];

        /** @var  $greetInput */
        $greetInput = new ArrayInput($arguments);

        return $command->run($greetInput, $output);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function runRequireUpdateMODXPackages(InputInterface $input, OutputInterface $output)
    {
        $packages = MODXPackagesConfig::getPackages();

        $interactionHandler = '';
        if (isset($this->command->consoleUserInteractionHandler) && $this->command->consoleUserInteractionHandler instanceof UserInteractionHandler) {
            /** @var ConsoleUserInteractionHandler $interactionHandler */
            $interactionHandler = $this->command->consoleUserInteractionHandler;
        } else {
            $interactionHandler = new ConsoleUserInteractionHandler($input, $output);
        }

        $modxPackages = new MODXPackages($this->modx, $interactionHandler);

        foreach ($packages as $name => $info) {
            try {
                $modxPackages->requirePackage($info['signature'], $info['latest'], $info['provider']);

            }  catch (TransportNotFoundException $exception) {
                $output->writeln('Error: '.$exception->getMessage());

            } catch (TransportException $exception) {
                $output->writeln('Error: '.$exception->getMessage());
            }
        }
    }

    /**
     * Override this method to run any custom scripts before Deploy->run
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function runBefore(InputInterface $input, OutputInterface $output)
    {
        // Could have a backup database script here
    }

    /**
     * Override this method to run any custom scripts after deploy
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function runAfter(InputInterface $input, OutputInterface $output)
    {
        // If you are using stockpile could have this as a confirmation Y/N
        // Elastic search
    }
}
