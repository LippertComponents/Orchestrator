<?php
/**
 * Simple setup script to allow testing on https://scrutinizer-ci.com
 */
$root_path = dirname(dirname(__DIR__));
$modx_path = $root_path . '/revolution/';

// 1. Build the .env file  to allow running tests and where the MODX is located
$tests_path = dirname(__DIR__) . DIRECTORY_SEPARATOR;

$env_contents = 'MODX_CONFIG_PATH="'.$modx_path.'config.core.php"
LCI_ORCHESTRATOR_MIGRATION_PATH="' . $root_path . '/src/"';

file_put_contents($root_path . '.env', $env_contents);

// 2. create MODX setup/config.xml so MODX installer can run
$setup_config_path = $modx_path . 'setup/config.xml';
$mode = 'new';

$config_data = [
    'database_type' => 'mysql',
    'database_server' => 'localhost',
    'database' => 'modx_ci',
    'database_user' => 'root',
    'database_password' => '',
    'database_connection_charset' => 'utf8',
    'database_charset' => 'utf8',
    'database_collation' => 'utf8_general_ci',
    'table_prefix' => 'modx_',
    'https_port' => 443,
    'http_host' => 'localhost',
    'inplace' => 0,
    'unpacked' => 0,
    'language' => 'en',
    'cmsadmin' => 'admin',
    'cmspassword' => 'simplePassword',
    'cmsadminemail' => 'a@email.com',
    'context_web_path' => $modx_path . '/',
    'context_web_url' => '/',
    'core_path' => $modx_path . '/core/',
    'context_mgr_path' => $modx_path . '/manager/',
    'context_mgr_url' => '/manager/',
    'context_connectors_path' => $modx_path . '/connectors/',
    'context_connectors_url' => '/connectors/',
    'remove_setup_directory' => 1,
];

// Generate config file
$xml = new SimpleXMLElement('<modx/>');
foreach ($config_data as $key => $value) {
    $xml->addChild($key, $value);
}
file_put_contents($setup_config_path, $xml->asXML());

// 2.  Install MODX
$argv = [
    $modx_path . 'index.php',
    '--installmode=' . $mode,
    '--core_path=' . $config_data['core_path'],
    '--config=' . $setup_config_path,
];
require($modx_path . 'setup/index.php');
// Don't put anything after MODX setup, it may exit the script