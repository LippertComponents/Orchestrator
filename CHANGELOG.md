# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.3.1](https://github.com/LippertComponents/Orchestrator/compare/v1.3.0...v1.3.1) - 2019-06-26
### Changed
 - Add $modx as construct param to Deploy and DeployInterface
 - Add check if custom deploy class implements DeployInterface

## [1.3.0](https://github.com/LippertComponents/Orchestrator/compare/v1.2.0...v1.3.0) - 2019-06-25

### Added
- Added Deploy command `orchestrator:deploy` which will clear MODX cache, run all defined package migrations and local migrations.  
There is also an option to set a custom deploy class in the env file, with the LCI_MODX_ORCHESTRATOR_DEPLOY_EXTENDED_CLASS setting.
- Added a lci_orchestrator_package.php config file to manage orchestrator packages that will run migrations from commands.  
Deprecated the composer.json -> extra -> auto-install definition.
- Added static method for packages to require dependencies via composer and then add it to the Orchestrator package list, 
to run that package Blend migrations. `Orchestrator::addDependantPackageToConfig('lci/stockpile');`

## [1.2.0](https://github.com/LippertComponents/Orchestrator/compare/v1.1.0...v1.2.0) - 2019-03-16

### Added
- Add UninstallPackages command: orchestrator:remove
- Update Blend min requirement to v1.2.0

## [1.1.0](https://github.com/LippertComponents/Orchestrator/compare/v1.0.0...v1.1.0) - 2019-03-08

### Added
- Add loading the .env file in the requireComposerAutoloader plugin.

## [1.0.0](https://github.com/LippertComponents/Orchestrator/releases/tag/v1.0.0) - 2018-10-16

- Update composer.json to Blend v1.0.0 and added LCI_ORCHESTRATOR_MIGRATION_PATH config option for tests
- Add basic test

## [1.0.0-beta6](https://github.com/LippertComponents/Orchestrator/releases/tag/v1.0.0-beta6) - 2018-10-09

- Fix ComposerHelper, set the correct lci/blend project name

## [1.0.0-beta5](https://github.com/LippertComponents/Orchestrator/releases/tag/v1.0.0-beta5) - 2018-10-09

- Fix for copy public/assets, set permissions to 0755 on copy

## [1.0.0-beta4](https://github.com/LippertComponents/Orchestrator/releases/tag/v1.0.0-beta4) - 2018-10-08

- Fix calls to Blend->runMigrations to first pass the related project 

## [1.0.0-beta3](https://github.com/LippertComponents/Orchestrator/releases/tag/v1.0.0-beta3) - 2018-10-05

Update Readme, formatting
Update Readme and to 1.0.0-beta3

- Fix ComposerHelper pre-package-uninstall to check the package to be removed
- Dependency update to Blend beta14 
