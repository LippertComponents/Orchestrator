---
layout: default
title: Package Development
nav_order: 5
has_children: false
---
# Package Development

Easily create a package that can be placed on [Packagist](https://packagist.org/) or [Private Packagist](https://packagist.com/).
First you will need a valid repository of your package code on github or bitbucket.

## Setup

You will need to create a composer.json file that defines your project and your project namespaces. For help read 
[Starting a New PHP Package The Right Way](https://www.sitepoint.com/starting-new-php-package-right-way/) and for quick
reference [a skeleton repository](https://github.com/thephpleague/skeleton)

### Your project directory structure

Follow the [PSR-4 Autoloader](https://www.php-fig.org/psr/psr-4/) and all recommended composer package file structure. Your project 
will look like the following, similar to the examples packages above plus some MODX specific directories, elements, lexicon, model and processors.
``` 
public/
src/
  database/
     migrations/
         m2019_08_16_180000_InstallMyPackage.php
     seeds/
  elements/
     chunks/
     snippets/
     plugins/
     templates/
  lexicon/
  model/ ~ still need non namespaced xPDO classes
  processors/ ~ still need non namespaced MODX classes
  --
  Proper Namespace for your PHP Classes
```

Contents in the public directory will be copied to the public web root directory on any of the following commands:
- `php vendor/bin/orchestrator deploy`
- `php vendor/bin/orchestrator orch:package my/package`
- For the advanced setup for each related composer install/require/update. 

Optionally you can use assets as a director rather then public. The contents with in your packages assets directory will 
then be copied into the assets directory in the public web root. 


## Migrations

Migrations are the way to install all MODX elements like chunks and snippets as well as write any custom code you need to 
run to install or update your package.

To generate a migration class do the following on your local environment without requiring a MODX instance do:

```
cd /www/core/
php vendor/bin/blend blend:generate -N InstallMyPackage -p src/
```

This will create a file with a date timestamp that would be something like `scr/database/migrations/m2019_08_16_180000_InstallMyPackage.php`.
Open that file up in PHPStorm to begin creating your migration.

### Run a single package's Migrations 

```
cd /www/core/
php vendor/bin/orchestrator orchestrator:package lci/blend
``` 

### Elements in migrations

Best practice is to set your elements as static. For static elements that are installed via a migration, they will not 
need any further migration for code related changes as MODX will look for that file and rebuild the code from it.

An example migration up and down methods to create a new chunk on install and remove the chunk on uninstall:

```php
<?php
/**
 * Run the migrations.
 *
 * @return void
 */
public function up()
{
    /** @var \LCI\Blend\Blendable\Chunk $myChunk */
    $myChunk = $this->blender->getBlendableLoader()->getBlendableChunk('myChunk');
    
    // replace my/package with your Composer Package name as listed on your composer.json file
    // Then the full relative file path of your file
    $myChunk
        ->setSeedsDir($this->getSeedsDir())// This is needed to set the down() data
        ->setFieldDescription('This is my test chunk, note this is limited to 255 or something')
        ->setFieldCategory('My Site=>Chunks')
        //  file path                                          Media Source name, orchestrator will put it in the correct place
        ->setAsStatic('my/package/src/elements/chunks/myChunk.tpl', 'orchestrator');
    
    // The blend() method will create a back/down data before saving to allow for easy revert with the revertBlend method
    if ($myChunk->blend(true)) {
        $this->blender->out($myChunk->getFieldName().' was saved correctly');
    
    } else {
        //error
        $this->blender->outError($myChunk->getFieldName().' did not save correctly ');
        $this->blender->outError(print_r($myChunk->getErrorMessages(), true), \LCI\Blend\Blender::VERBOSITY_DEBUG);
    }
}

/**
 * Reverse the migrations.
 *
 * @return void
 */
public function down()
{
    /** @var \LCI\Blend\Blendable\Chunk $myChunk */
    $myChunk = $this->blender->getBlendableLoader()->getBlendableChunk('myChunk');
    $myChunk->setSeedsDir($this->getSeedsDir());// This is needed to retrieve the down data
    
    if ( $myChunk->revertBlend() ) {
        $this->blender->out($myChunk->getFieldName().' chunk has been reverted to '.$this->getSeedsDir());
    
    } else {
        $this->blender->outError($myChunk->getFieldName().' chunk was not reverted');
    }
}
```


## Plugins and Snippets

Now that we have the autoload set up properly we can now use that in both Plugins and Snippets. For the example project 
lets create a PHP Class named SomeAwesomeClass. Create the file in `src/SomeAwesomeClass.php` the contents of the file
will look like so:
```php
<?php
namespace SomeCompany\MODX\ExampleProject;
use modX;

class SomeAwesomeClass
{
    /** @var modX */
    protected $modX;

    public function __construct(modX $modX) 
    {
        $this->modX = $modX;
    }
    
    public function doSomething()
    {
        return 'I did something!';
    }
}
```

Now you can create the snippet that will load this class via the autoloader. Let's name the snippet `doSomething` and with
the following contents:

```php
<?php
use SomeCompany\MODX\ExampleProject\SomeAwesomeClass;

$someAwesomeClass = new SomeAwesomeClass($modx);

return $someAwesomeClass->doSomething();
```

Since Orchestrator has loaded the autoloader for us already we do not need to do anything special to include the related class
files. Plugins will work the same way.

### REST

MODX uses connectors to do much of the work for like Ajax requests, but now you have an option to use REST routes with Slim.
REST will require the [MODX-Slim](https://github.com/LippertComponents/MODX-Slim) package.


## Running the project code before a release

One way to run your package code is that once you have put your code in github or bitbucket, you can now require that repository
via composer. And you can specify what branch you would like to pull in. See the example below on how to include your package
from a repository. After you update your composer.json file then run composer update and composer will get the code for you.

Verify your work and then you can make edits, commit them and then simply do composer update. And repeat as needed.

> Note: you will need to publish your package on Packagist as noted below to allow others to require your package in their work.

Example of composer.json file to pull in the github code for the lci/menu-builder package from the master branch:

```json
{
  "require": {
    "lci/modx-menubuilder": "dev-master"
  },
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/LippertComponents/MenuBuilder"
    }
  ],
  "minimum-stability": "dev"
}
```

Another option would be to move the package files via SFTP or other methods to the /core/vendor/Package/Name/ directory. Note
this is more challenging as you will need to 

## Release a package

- Use [Semantic Versioning](https://semver.org/)
- [Keep a changelog](https://keepachangelog.com)

This is the easy part, in git just give it a tag and then push the tag to your github or bitbucket repository. Like so:
```
git tag v1.0.0
git push origin v1.0.0
```

Then submit it to [Packagist](https://packagist.org/). That's it! 
