---
layout: default
title: MODX Extras
nav_order: 8
has_children: false
---
# MODX Extras

Use Orchestrator to install or update MODX transport extras. [Config](customization.md#config-files)

## List of installed Extras

```
cd /www/core/
php vendor/bin/orchestrator modx:list-packages
```

## Install/Update an Extra

Example of installing the Ace extra:

```
cd /www/core/
 php vendor/bin/orchestrator modx:package ace-1.8.0-pl
```

Once installed from command line then a related entry will be made in the Orchestrator config file: `core/config/lci_modx_transport_package.php`.
Commit this file to git if you are using a git workflow. Also if you open the file up you will see that for each extra
there are three data values. The key `latest` is boolean and if set to false it will not update past that version so you 
can set to a fixed version. 

> Note if you do install via the MODX Manager those extras will not be added to the config file.


Example of installing several extras at the same time:

```
cd /www/core/
php vendor/bin/orchestrator modx:package ace-1.8.0-pl,collections-3.6.0-pl,seopro-1.3.0-pl,stercseo-2.2.0-pl,tagger-1.11.0-pl,tinymcerte-1.3.2-pl,versionx-2.2.1-pl
```

Orchestrator will also attempt to do a search if you do not pass the full signature. For example if you ran the following,
it would install Fred.

```
cd /www/core/
php vendor/bin/orchestrator modx:package fred
```

## Require Extras via a migration

In a migration file add the related in you `up` and `down` methods:

```php
<?php
use LCI\Blend\Transport\MODXPackagesConfig;

// ...

/**
 * Run the migrations.
 *
 * @return void
 */
public function up()
{
    MODXPackagesConfig::addPackageConfig('ace-1.8.0-pl', true, 'modx.com');
    MODXPackagesConfig::addPackageConfig('fred-1.0.0-pl', true, 'modx.com');
    // list as many as you would like installed
}

/**
 * Reverse the migrations.
 *
 * @return void
 */
public function down()
{
    MODXPackagesConfig::removePackageConfig('ace-1.8.0-pl');
    MODXPackagesConfig::removePackageConfig('fred-1.0.0-pl');
}
```

## Deploy

All extras defined in the Orchestrator transport config file: `core/config/lci_modx_transport_package.php` will be installed
or updated if available on the deploy command. Local extra cannot be set in the config.

```
cd /www/core/
php vendor/bin/orchestrator deploy
```
