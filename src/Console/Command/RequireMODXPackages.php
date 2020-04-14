<?php

namespace LCI\MODX\Orchestrator\Console\Command;

use LCI\Blend\Transport\MODXPackages;
use LCI\MODX\Console\Command\BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RequireMODXPackages extends BaseCommand
{
    public $loadMODX = true;

    protected function configure()
    {
        $this
            ->setName('modx:package')
            ->setDescription('Require or Remove a MODX Extra Package')
            ->addArgument(
                'signature',
                InputArgument::REQUIRED,
                'Enter a valid package signature, like ace-1.8.0-pl. Multiple packages can separated by a comma.'
            )
            ->addOption(
                'provider',
                'p',
                InputOption::VALUE_OPTIONAL,
                'Transport provider name, default is modx.com. For more info see: https://docs.modx.com/revolution/2.x/developing-in-modx/advanced-development/package-management/providers',
                'modx.com'
            )
            ->addOption(
                'latest',
                'l',
                InputOption::VALUE_OPTIONAL,
                'Get the latest version of this extra.',
                true
            )
            ->addOption(
                'method',
                'm',
                InputOption::VALUE_OPTIONAL,
                'Options are install, remove or uninstall. Remove will uninstall and remove the package files. Uninstall will preserve',
                'install'
            );
    }

    /**
     * Runs the command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \LCI\Blend\Exception\TransportException
     * @throws \LCI\Blend\Exception\TransportNotFoundException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $signatures = explode(',', $input->getArgument('signature'));
        $provider_name = $input->getOption('provider');
        $latest = (bool)$input->getOption('latest');
        $method = $input->getOption('method');

        $modx = $this->console->loadMODX();
        $modxPackages = new MODXPackages($modx, $this->consoleUserInteractionHandler);

        foreach ($signatures as $signature) {

            switch (strtolower(trim($method))) {
                case 'remove':
                    $output->writeln('### Orchestrator is attempting to remove the MODX Extra Package: ' . $signature . ' and for the provider: ' . $provider_name . '  ###');
                    // uninstall/remove
                    $modxPackages->removePackage($signature);

                break;
                case 'uninstall':
                    $output->writeln('### Orchestrator is attempting to uninstall the MODX Extra Package: ' . $signature . ' and for the provider: ' . $provider_name . '  ###');
                    // uninstall/remove
                    $modxPackages->unInstallPackage($signature);
                    break;

                default:
                $output->writeln('### Orchestrator is attempting to require the MODX Extra Package: ' . $signature . ' and for the provider: ' . $provider_name . '  ###');

                $modxPackages->requirePackage($signature, $latest, $provider_name);
            }
        }

        $output->writeln($this->getRunStats());
		return 0;
    }
}
