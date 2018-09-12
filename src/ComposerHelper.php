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
     */
    public static function install(Event $event)
    {
        $args = $event->getArguments();

        /** @var Composer $composer */
        $composer = $event->getComposer();

        $vendorDir = $composer->getConfig()->get('vendor-dir');
        require $vendorDir . '/autoload.php';

        Orchestrator::install();

        /** @var Package $package */
        $package = $composer->getPackage();
        $extra = $package->getExtra();

        if (isset($extra['auto-install']) && is_array($extra['auto-install'])) {
            foreach ($extra['auto-install'] as $orchestrator_package) {
                Orchestrator::installComposerPackage($orchestrator_package);
            }
        }
    }

    /**
     * @param Event $event
     */
    public static function update(Event $event)
    {
        $args = $event->getArguments();
        /** @var Composer $composer */
        $composer = $event->getComposer();
        $vendorDir = $composer->getConfig()->get('vendor-dir');
        require $vendorDir . '/autoload.php';

        Orchestrator::install();

        /** @var Package $package */
        $package = $composer->getPackage();
        $extra = $package->getExtra();

        if (isset($extra['auto-install']) && is_array($extra['auto-install'])) {
            foreach ($extra['auto-install'] as $orchestrator_package) {
                Orchestrator::updateComposerPackage($orchestrator_package);
            }
        }
    }

    /**
     * @param PackageEvent $event
     */
    public static function uninstall(PackageEvent $event)
    {
        $args = $event->getArguments();
        /** @var Composer $composer */
        $composer = $event->getComposer();
        $vendorDir = $composer->getConfig()->get('vendor-dir');
        require $vendorDir . '/autoload.php';

        Orchestrator::uninstall();

        /** @var Package $package */
        $package = $composer->getPackage();
        $extra = $package->getExtra();

        if (isset($extra['auto-install']) && is_array($extra['auto-install'])) {
            foreach ($extra['auto-install'] as $orchestrator_package) {
                Orchestrator::uninstallComposerPackage($orchestrator_package);
            }
        }
    }
}