---
layout: default
title: Blend Migrations
nav_order: 5
has_children: true
permalink: blend
---
# Blend Migrations

[![Build Status](https://scrutinizer-ci.com/g/LippertComponents/Blend/badges/build.png?b=master)](https://scrutinizer-ci.com/g/LippertComponents/Blend/)

The Blend project aims to import/export resources and elements from one MODX instance to another. Allowing developers to
work with workflows from local > dev > staging > production(master). Blend attempts to be similar to what Migrate is
for Laravel.

**This is a CLI tool. Most code references are for SSH/command line/terminal. To create custom Migrations you
should be knowledgeable of PHP and MODX. A good IDE like PHPStorm will help you auto complete available methods for
custom migrations. Using the [Symfony/Console](https://symfony.com/doc/3.4/components/console.html) component for CLI.**

## Goals

1. IDE driven, build out a blank migration file and then be able to use an IDE like PHPStorm to help you out. And
provide convenience methods to speed up the process and make it consistent.
2. To make VCS(git) workflows easily between local, remote, dev, staging and production
3. Abstracting IDs from xPDO relationships for storage. Allow passing of MODX resources(site content) and TVs
independent of IDs but becoming dependant on alias, the alias must then be unique across environments.
4. Build a MODX dev box/branch that will load in all essential data and create as many users and user groups as
needed for testing.
5. Allowing smooth rollbacks, if dev to prod failed, roll it back to the latest know working version

## Introduction

- What are Migrations?
Think of migrations as creating instructions for data to be imported or modified.
Migrations can be used like version control for the MODX database, allowing your team to easily modify and share the changes to
elements (chunks, plugins, snippets, templates and template variables), resource(pages) and system settings as well as your
custom tables. If you manually migrate a MODX element from dev to production, then migrations will help you track and ensure
consistent results.

- What are Seeds?
Currently seeds are generated files that contain the selected elements (chunks, plugins, snippets, templates and
template variables), resource(pages) and/or system settings as data exports of a MODX instance that can be used in another 
instance. For example moving from development server to production.

## Install

Blend is installed with [Orchestrator](../index.md)

## Generate a migration

See the [Local](../local-development.md) or [Package](../package-development.md) development docs for more info.


## Example migrations

- [Chunks](chunks.md)
- [Plugins](plugins.md)
- [Resources](resources.md)
- [Seed Site](seed-site.md)
- [Snippets](snippets.md)
- [System Settings](system-settings.md)
- [Templates](templates.md)
- [Template Variables](template-variables.md)

For more examples see the Blend repo [tests/database/migrations](https://github.com/LippertComponents/Blend/tree/master/tests/database/migrations) 
directory.
