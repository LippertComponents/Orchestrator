<?php
/**
 * Created by PhpStorm.
 * User: joshgulledge
 * Date: 9/3/18
 * Time: 11:33 AM
 */

namespace LCI\MODX\Orchestrator;

use LCI\Blend\Blender;
use LCI\MODX\Console\Console;
use LCI\MODX\Console\Helpers\VoidUserInteractionHandler;

class Orchestrator
{
    /** @var \modX */
    public static $modx;

    /**
     *
     */
    public static function install()
    {
        $path = static::getPackagePath();

        self::runMigrations(['blend_modx_migration_dir' => $path]);
    }

    /**
     *
     */
    public static function uninstall()
    {
        /** @var \LCI\MODX\Console\Console $console */
        $console = new Console();
        // 1. install BLend
        $handler = new VoidUserInteractionHandler();

        $blender = new Blender($console->loadMODX(), $handler, ['blend_modx_migration_dir' => __DIR__]);

        if (!$blender->isBlendInstalledInModx()) {
            $blender->install();
        }

        // 2. run Migrations ~ install & update
        $blender->runMigration('down');

        $blender->install('down');
    }

    /**
     * @param $dir
     * @param string $destination
     */
    public static function copyAssets($dir, $destination=MODX_ASSETS_PATH)
    {
        // @TODO
    }

    /**
     * @param $project
     * @param $file
     *
     * assume a MODX structure for elements
     * public/
     * src/
     *   elements/
     *      chunks/
     *      snippets/
     *      plugins/
     *      templates/
     *   lexicon/
     *   modal/ ~ ??? still need non namespaced xPDO classes
     *   processors/ ~ still need non namespaced MODX classes
     *  Namespaced PHP Classes
     *
     * the core/components/project/docs is replaced with a root
     * README.md
     * Changelog.md
     * license can just be defined in the composer.json file
     */
    /**
     * @param string $project ~ a valid composer project like lci/blend
     * @param string $type
     */
    public static function installComposerPackage($project, $type='master')
    {
        $path = static ::getPackagePath($project);

        self::runMigrations(['blend_modx_migration_dir' => $path], 'up', $type);
    }

    /**
     * @param string $project ~ a valid composer project like lci/blend
     * @param string $type
     */
    public static function updateComposerPackage($project, $type='master')
    {
        $path = static ::getPackagePath($project);

        self::runMigrations(['blend_modx_migration_dir' => $path], 'up', $type);
    }

    /**
     * @param string $project ~ a valid composer project like lci/blend
     * @param string $type
     */
    public static function uninstallComposerPackage($project, $type='master')
    {
        $path = static ::getPackagePath($project);

        self::runMigrations(['blend_modx_migration_dir' => $path], 'down', $type);
    }

    /**
     * @param array $config
     * @param string $method
     * @param string $type
     */
    protected static function runMigrations($config=[], $method='up', $type='master')
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

        // 2. run Migrations
        $blender->runMigration($method, $type);
    }

    /**
     * @param string $package
     * @return string
     */
    protected static function getPackagePath($package='lci/orchestrator')
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
        $path .= $package . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;

        return $path;
    }
}