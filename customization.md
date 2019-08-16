---
layout: default
title: Customizations
nav_order: 3
has_children: false
---
# Customizations 

Configure all customizations within the core/.env file. Any Orchestrator package can have custom settings defined here.
If you do not have a .env file located in the core directory then create it.

Property | Description | Default
--- | --- | --- 
BLEND_LOCAL_MIGRATION_PATH | Path to where you would like local Blend Migration files | MODX_CORE_PATH.'components/blend/'
**Orchestrator packages install & update** | | 
LCI_ORCHESTRATOR_ASSETS_PATH | Path in your project where files are copied into from an Orchestrator package assets directory | MODX_ASSETS_PATH
LCI_ORCHESTRATOR_PUBLIC_PATH | Path in your project where files are copied into from an Orchestrator package public directory | MODX_BASE_PATH
**Orchestrator Install/Upate Migrations** |  | 
LCI_ORCHESTRATOR_MIGRATION_PATH | Path to src of Orchestrator, set only if you did a git clone rather than a composer install | path to vendor src dir
**Orchestrator Install Only** |  |  
LCI_ORCHESTRATOR_BASE_PATH | Relative path to the composer vendor directory to define the MediaSource on install | core/vendor/
LCI_ORCHESTRATOR_BASE_URL | Relative path to the composer vendor directory to define the MediaSource on install | core/vendor/
LCI_ORCHESTRATOR_VENDOR_PATH | Path to the composer vendor directory | MODX_CORE_PATH .'vendor/'
**Optional** |  |  
LCI_MODX_ORCHESTRATOR_DEPLOY_EXTENDED_CLASS | [FQN](https://www.php.net/manual/en/language.namespaces.rules.php) of a Custom class that implements LCI\MODX\Orchestrator\Deploy\DeployInterface | LCI\MODX\Orchestrator\Deploy\Deploy


## Config files

Orchestrator stores some additional configuration files in the core/config/ directory. These files are created automatically
but you can also modify them. 

- The `core/config/lci_console_env.php` has the full path to MODX core. This file will be unique per install.
- The `lci_console_package_commands.php` file holds all of the packages that have commands to be used in Orchestrator.  
Example:  
    ```php
    <?php 
    return array (
      0 => 'LCI\\Blend\\Console\\ActivePackageCommands',
      1 => 'LCI\\MODX\\Orchestrator\\Console\\ActivePackageCommands',
      2 => 'LCI\\MODX\\Stockpile\\Console\\ActivePackageCommands',
    );
    
    ```
- The `core/config/lci_modx_transport_package.php` file holds all info for MODX Extras that are to be installed/updated on
deploy.
Example:  
    ```php
    <?php 
    return array (
      'ace' => 
      array (
        'signature' => 'ace-1.8.0-pl',
        'latest' => true,
        'provider' => 'modx.com',
      ),
      'collections' => 
      array (
        'signature' => 'collections-3.6.0-pl',
        'latest' => true,
        'provider' => 'modx.com',
      ),
    );
    ```
- The `core/config/lci_orchestrator_package.php` file holds all composer package names that have been set up as an 
Orchestrator package. These packages will be checked for new migration files on deploy.  
Example:
    ````php
    <?php 
    return array (
      0 => 'lci/orchestrator',
      1 => 'lci/blend',
      2 => 'lci/stockpile',
      3 => 'lci/modx-image-helper',
    );
    ````
