<?php

namespace LCI\MODX\Orchestrator\Console\Command;

use LCI\Blend\Exception\TransportException;
use LCI\Blend\Exception\TransportNotFoundException;
use LCI\Blend\Transport\MODXPackages;
use LCI\MODX\Console\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ListMODXPackages extends BaseCommand
{
    public $loadMODX = true;

    protected function configure()
    {
        $this
            ->setName('modx:list-packages')
            ->setAliases(['extras'])
            ->setDescription('Require a MODX Extra Package')
            ->addOption(
                'update',
                'u',
                InputOption::VALUE_OPTIONAL,
                'Update all packages',
                false
            )
            ->addOption(
                'latest',
                'l',
                InputOption::VALUE_OPTIONAL,
                'Get the latest version of this extra. Only used when updating all packages',
                true
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
        $update_all = (bool)$input->getOption('update');
        $latest = (bool)$input->getOption('latest');

        $modx = $this->console->loadMODX();
        $modxPackages = new MODXPackages($modx, $this->consoleUserInteractionHandler);

        $io = new SymfonyStyle($input, $output);


        $io->title('Installed MODX Extras');

        $current_packages = $modxPackages->getList(100);
        $packages = [];

        if ($current_packages['total'] > 0) {
            foreach ($current_packages['packages'] as $package) {
                $update = 'No';
                if (isset($package['updates']) && isset($package['updates']['count']) && $package['updates']['count'] > 0) {
                    $update = '';
                    foreach ($package['updates']['versions'] as $key => $version) {
                        $update .= ', ' . $version['version'];
                    }
                    $update = 'Yes ' . trim(trim($update, ','));
                }

                $installed = 'No';
                if (!is_null($package['installed']) && $package['installed'] != '0000-00-00 00:00:00') {
                    $installed = utf8_encode(date($modx->getOption('manager_date_format') . ', ' . $modx->getOption('manager_time_format'), strtotime($package['installed'])));
                }

                if ($update_all && $update != 'No') {
                    // update
                    $output->writeln('### Orchestrator is attempting to update the MODX Extra Package: ' . $package['signature'] .' ###');

                    try {
                        $modxPackages->requirePackage($package['signature'], $latest);

                    } catch (TransportNotFoundException $exception) {
                        $output->writeln('Error: '.$exception->getMessage());

                    } catch (TransportException $exception) {
                        $output->writeln('Error: '.$exception->getMessage());
                    }
                }

                $packages[] = [
                    $package['package_name'],
                    $package['signature'],
                    $package['metadata']['version'],
                    $installed,
                    $update
                ];
            }
        }
        $table_header = ['Name', 'Signature', 'Version', 'Installed', 'Update Available'];

        $io->table($table_header, $packages);


        $output->writeln($this->getRunStats());
    }
}
