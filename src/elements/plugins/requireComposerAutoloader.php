<?php
/**
 * Traditional MODX Plugin
 * Only attach to the OnInitCulture? event
 * @see https://docs.modx.com/revolution/2.x/developing-in-modx/basic-development/plugins/system-events
 */

/** @var \modX $modx */

if (defined('ORCHESTRATOR_AUTOLOAD') && ORCHESTRATOR_AUTOLOAD) {
    // already loaded do nothing
} else {
    /** @var string $file */
    $file = $modx->getOption('orchestrator.vendor_path', null, MODX_CORE_PATH . 'components/orchestrator/vendor/') . 'autoload.php';

    if (file_exists($file)) {
        require_once $file;

        \LCI\MODX\Console\Console::loadEnv();
    } else {
        $modx->log('', '[Orchestrator] composer autoload.php file was not found, check systems setting: orchestrator.vendor_path');
    }
}