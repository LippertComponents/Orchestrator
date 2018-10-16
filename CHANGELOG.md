# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2018-10-16

- Update composer.json to Blend v1.0.0 and added LCI_ORCHESTRATOR_MIGRATION_PATH config option for tests
- Add basic test

## [1.0.0-beta6] - 2018-10-09

- Fix ComposerHelper, set the correct lci/blend project name

## [1.0.0-beta5] - 2018-10-09

- Fix for copy public/assets, set permissions to 0755 on copy

## [1.0.0-beta4] - 2018-10-08

- Fix calls to Blend->runMigrations to first pass the related project 

## [1.0.0-beta3] - 2018-10-05

Update Readme, formatting
Update Readme and to 1.0.0-beta3

- Fix ComposerHelper pre-package-uninstall to check the package to be removed
- Dependency update to Blend beta14 