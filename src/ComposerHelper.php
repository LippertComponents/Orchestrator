<?php
/**
 * Created by PhpStorm.
 * User: joshgulledge
 * Date: 9/3/18
 * Time: 3:39 PM
 */


namespace LCI\MODX\Orchestrator;

use Composer\Script\Event;
use Composer\Installer\PackageEvent;

class ComposerHelper
{
    /**
     * @param PackageEvent $event
     */
    public static function install(PackageEvent $event)
    {
        $args = $event->getArguments();
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        require $vendorDir . '/autoload.php';

        Orchestrator::install();
    }

    /**
     * @param PackageEvent $event
     */
    public static function update(PackageEvent $event)
    {
        $args = $event->getArguments();
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        require $vendorDir . '/autoload.php';

        Orchestrator::install();
    }

    /**
     * @param PackageEvent $event
     */
    public static function uninstall(PackageEvent $event)
    {
        $args = $event->getArguments();
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        require $vendorDir . '/autoload.php';

        Orchestrator::install();
    }
}