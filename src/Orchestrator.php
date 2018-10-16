<?php
/**
 * Created by PhpStorm.
 * User: joshgulledge
 * Date: 9/3/18
 * Time: 11:33 AM
 */

namespace LCI\MODX\Orchestrator;

use LCI\Blend\Blender;
use LCI\Blend\Helpers\Files;
use LCI\MODX\Console\Console;
use LCI\MODX\Console\Helpers\VoidUserInteractionHandler;

class Orchestrator
{
    use Files;

    /** @var \modX */
    public static $modx;

    /**
     * @throws \LCI\Blend\Exception\MigratorException
     */
    public static function install()
    {
        $path = getenv('LCI_ORCHESTRATOR_MIGRATION_PATH');
        if (empty($path)) {
            $path = static::getPackagePath();
        }

        self::runMigrations('lci/orchestrator', ['blend_modx_migration_dir' => $path]);
    }

    /**
     * @throws \LCI\Blend\Exception\MigratorException
     */
    public static function uninstall()
    {
        /** @var \LCI\MODX\Console\Console $console */
        $console = new Console();
        // 1. install BLend
        $handler = new VoidUserInteractionHandler();

        $path = getenv('LCI_ORCHESTRATOR_MIGRATION_PATH');
        if (empty($path)) {
            $path = static::getPackagePath();
        }

        $blender = new Blender($console->loadMODX(), $handler, ['blend_modx_migration_dir' => $path]);

        if (!$blender->isBlendInstalledInModx()) {
            $blender->install();
        }

        // 2. run Migrations ~ install & update
        $blender->runMigration('down');

        $blender->install('down');
    }

    /**
     * @param string $package ~ ex: lci/stockpile
     * @TODO Review ~ this is called on via a post composer script set up in extra
     *
     */
    public static function copyAssets($package)
    {
        // public will copy into the MODX public root path
        // assets will keep the same pathing
        $package_path = self::getPackagePath($package, false);

        $console = new Console();
        $config = $console->getConfig();

        $self = new Orchestrator();
        $self->setMode(0755);

        if (file_exists($package_path . 'public')) {
            $destination = MODX_BASE_PATH;
            if (isset($config['LCI_ORCHESTRATOR_PUBLIC_PATH']) && file_exists($config['LCI_ORCHESTRATOR_PUBLIC_PATH'])) {
                $destination = $config['LCI_ORCHESTRATOR_PUBLIC_PATH'];
            }

            $self->copyDirectory($package_path . 'public', $destination);
        }

        if (file_exists($package_path . 'assets')) {
            $destination = MODX_ASSETS_PATH;
            if (isset($config['LCI_ORCHESTRATOR_ASSETS_PATH']) && file_exists($config['LCI_ORCHESTRATOR_ASSETS_PATH'])) {
                $destination = $config['LCI_ORCHESTRATOR_ASSETS_PATH'];
            }

            $self->copyDirectory($package_path . 'assets', $destination);
        }
    }

    /**
     * @param string $project ~ a valid composer project like lci/blend
     * @param string $type
     *
     * @throws \LCI\Blend\Exception\MigratorException
     */
    public static function installComposerPackage($project, $type='master')
    {
        $path = static ::getPackagePath($project);

        self::copyAssets($project);
        self::runMigrations($project, ['blend_modx_migration_dir' => $path], 'up', $type);
    }

    /**
     * @param string $project ~ a valid composer project like lci/blend
     * @param string $type
     * @throws \LCI\Blend\Exception\MigratorException
     */
    public static function updateComposerPackage($project, $type='master')
    {
        $path = static ::getPackagePath($project);

        self::copyAssets($project);
        self::runMigrations($project, ['blend_modx_migration_dir' => $path], 'up', $type);
    }

    /**
     * @param string $project ~ a valid composer project like lci/blend
     * @param string $type
     * @throws \LCI\Blend\Exception\MigratorException
     */
    public static function uninstallComposerPackage($project, $type='master')
    {
        $path = static ::getPackagePath($project);

        // @TODO remove assets
        self::runMigrations($project, ['blend_modx_migration_dir' => $path], 'down', $type);
    }

    /**
     * @param string $project
     * @param array $config
     * @param string $method
     * @param string $type
     *
     * @throws \LCI\Blend\Exception\MigratorException
     */
    protected static function runMigrations($project, $config=[], $method='up', $type='master')
    {
        /** @var \LCI\MODX\Console\Console $console */
        $console = new Console();
        static::$modx = $console->loadMODX();

        // 1. install BLend
        $handler = new VoidUserInteractionHandler();

        $blender = new Blender(static::$modx, $handler, $config);

        if (!$blender->isBlendInstalledInModx()) {
            $blender->install();
        }

        $blender->setProject($project);

        // 2. run Migrations
        $blender->runMigration($method, $type);
    }

    /**
     * @param string $package
     * @param bool $include_src
     * @return string
     */
    protected static function getPackagePath($package='lci/orchestrator', $include_src = true)
    {
        if (empty(static::$modx)) {
            /** @var \LCI\MODX\Console\Console $console */
            $console = new Console();
            static::$modx = $console->loadMODX();
        }

        $path = static::$modx->getOption(
            'orchestrator.vendor_path',
            null,
            (defined('MODX_CORE_PATH') ? MODX_CORE_PATH.'vendor/' : dirname(__DIR__))
        );
        $path .= $package . DIRECTORY_SEPARATOR;

        if ($include_src) {
            $path .= 'src' . DIRECTORY_SEPARATOR;
        }

        return $path;
    }
}