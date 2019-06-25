<?php
/**
 * Created by PhpStorm.
 * User: joshgulledge
 * Date: 9/3/18
 * Time: 3:39 PM
 */


namespace LCI\MODX\Orchestrator;

use Composer\Composer;
use Composer\Script\Event;
use Composer\Installer\PackageEvent;
use Composer\Package\Package;

class ComposerHelper
{
    /**
     * @param Event $event
     * @throws \LCI\Blend\Exception\MigratorException
     */
    public static function install(Event $event)
    {
        $args = $event->getArguments();

        /** @var Composer $composer */
        $composer = $event->getComposer();

        $vendorDir = $composer->getConfig()->get('vendor-dir');
        require $vendorDir . '/autoload.php';

        Orchestrator::installComposerPackage('lci/blend');
        Orchestrator::install();

        /** @var Package $package */
        $package = $composer->getPackage();
        $extra = $package->getExtra();

        /** @deprecated in favor of deploy commands */
        if (isset($extra['auto-install']) && is_array($extra['auto-install'])) {
            foreach ($extra['auto-install'] as $orchestrator_package) {
                Orchestrator::installComposerPackage($orchestrator_package);
            }
        } else {
            Orchestrator::updateAllOrchestratorComposerPackages();
        }
    }

    /**
     * @param Event $event
     * @throws \LCI\Blend\Exception\MigratorException
     */
    public static function update(Event $event)
    {
        $args = $event->getArguments();
        /** @var Composer $composer */
        $composer = $event->getComposer();
        $vendorDir = $composer->getConfig()->get('vendor-dir');
        require $vendorDir . '/autoload.php';

        Orchestrator::install();
        Orchestrator::updateComposerPackage('lci/blend');

        /** @var Package $package */
        $package = $composer->getPackage();
        $extra = $package->getExtra();

        /** @deprecated in favor of deploy commands */
        if (isset($extra['auto-install']) && is_array($extra['auto-install'])) {
            foreach ($extra['auto-install'] as $orchestrator_package) {
                Orchestrator::updateComposerPackage($orchestrator_package);
            }
        } else {
            Orchestrator::updateAllOrchestratorComposerPackages();
        }
    }

    /**
     * @param PackageEvent $event
     * @throws \LCI\Blend\Exception\MigratorException
     */
    public static function uninstall(PackageEvent $event)
    {
        $args = $event->getArguments();

        /** @var \Composer\Package\BasePackage $package ~ current package */
        $currentPackage = $event->getOperation()->getPackage();

        /** @var Composer $composer */
        $composer = $event->getComposer();

        /** @var Package $package */
        $localPackage = $composer->getPackage();

        // this is the root composer.json data
        $vendorDir = $composer->getConfig()->get('vendor-dir');

        if (file_exists($vendorDir . '/autoload.php')) {
            require $vendorDir . '/autoload.php';
        }

        if ($currentPackage->getName() == 'lci\orchestrator') {
            Orchestrator::uninstall();
            // --leave-blend
            if (!isset($args['leave-blend'])) {
                Orchestrator::uninstallComposerPackage('lci/blend');
            }

        } elseif (self::isValidAutoInstall($currentPackage->getName(), $localPackage->getExtra())) {
            Orchestrator::uninstallComposerPackage($currentPackage->getName());
        }
    }

    /**
     * @deprecated in favor of deploy commands
     *
     * @param $package_name
     * @param $extra
     * @return bool
     */
    protected static function isValidAutoInstall($package_name, $extra)
    {
        if (isset($extra['auto-install']) && is_array($extra['auto-install']) && in_array($package_name, $extra['auto-install'])) {
            return true;
        }

        return false;
    }

}
