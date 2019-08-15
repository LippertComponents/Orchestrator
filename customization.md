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
