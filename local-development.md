---
layout: default
title: Local Development
nav_order: 3
has_children: false
---
# Local Development

Now you can take advantage of PHP namespaces and auto loading when creating a website in MODX! This will make OOP development 
much easier with reducing the amount of code in the header of your files that you need to include dependent scripts.

## Set up the core/composer.json

1. First you will need to choose a proper PHP namespace. For help review the [PSR-4 Autoloader](https://www.php-fig.org/psr/psr-4/).  
We are using Local\MODX\Website as the namespace.
2. Recommended that you put your code in the `core/components/local/` directory as in a normal project. Then you can have the 
chunks, plugins, snippets, templates as sub directories as traditionally done. 
3. Your PHP classes then should start at the directly with in the `core/components/local/` directory. And you can then 
nest your classes in PSR-4 named folders as needed.
4. Now update the `core/composer.json` file, below is an example:
    ```json
    {
      "require": {
        "lci/orchestrator": "^1.4.0",
        "lci/stockpile": "^1.4.0"
      },
      "autoload": {
        "psr-4": {
          "Local\\MODX\\Website\\": "components/local/"
        }
      },
      "minimum-stability": "dev",
      "prefer-stable": true
    }
    ```
5. Now you need to run composer update to regenerate the autoload class.
    ```
    cd /wwww/core/
    composer update
    ```
## MODX Elements

Create a migration to install all MODX elements, this will allow you to go from dev to production with just running a single
command on each server to install your Elements. See the migrations info below.

## Plugins and Snippets

Now that we have the autoload set up properly we can now use that in both Plugins and Snippets. For the example project 
lets create a PHP Class named MyWebsite. Create the file in `core/components/local/MyWebsite.php` the contents of the file
will look like so:
```php
<?php
namespace Local\MODX\Website;
use modX;

class MyWebsite
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
use Local\MODX\Website\MyWebsite;

$myWebsite = new MyWebsite($modx);

return $myWebsite->doSomething();
```

Since Orchestrator has loaded the autoloader for us already we do not need to do anything special to include the related class
files. Plugins will work the same way.


## Migrations

Migrations are the way to install all MODX elements like chunks and snippets as well as write any custom code you need to 
run to install or update your web project.

To generate a migration class do the following on your local environment do:

```
cd /www/core/
php vendor/bin/blend blend:generate -N InstallMyChunks -p components/blend/
```

This will create a file with a date timestamp that would be something like `core/components/blend/database/migrations/m2019_08_16_180000_InstallMyChunks.php`.
Open that file up in PHPStorm to begin creating your migration.

### Run your local project migrations 

```
cd /www/core/
php vendor/bin/orchestrator blend:migrate
``` 

Local migrations as well as all package migrations are also ran with the deploy command.

```
cd /www/core/
php vendor/bin/orchestrator deploy
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
        ->setFieldDescription('This is my local test chunk, note this is limited to 255 or something')
        ->setFieldCategory('My Site=>Chunks')
        //  file path                                          Media Source name, assuming you do not change the default MODX file system media source:
        ->setAsStatic('core/components/local/elements/chunks/myChunk.tpl', 'filesystem');
    
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
