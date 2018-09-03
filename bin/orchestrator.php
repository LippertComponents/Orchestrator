<?php
ini_set('display_errors', 1);

use LCI\MODX\Console\Console;
use LCI\MODX\Orchestrator\Console\Application;

$bootstrap_possible_paths = [
    // if cloned from git:
    dirname(__DIR__).'/src/bootstrap.php',
    // if installed via composer:
    dirname(dirname(dirname(__DIR__))).'/lci/orchestrator/src/bootstrap.php',
];
foreach ($bootstrap_possible_paths as $bootstrap_path) {
    if (file_exists($bootstrap_path)) {
        require_once $bootstrap_path;
        break;
    }
}

/**
 * Ensure the timezone is set;
 */
if (version_compare(phpversion(),'5.3.0') >= 0) {
    $tz = @ini_get('date.timezone');
    if (empty($tz)) {
        date_default_timezone_set(@date_default_timezone_get());
    }
}

$console = new Console();
/** @var Application $application */
$application = new Application($console);
$application->loadCommands();
$application->run();