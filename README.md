# Orchestrator

Allowing developers to use MODX along with composer managed packages utilizing Blend Migrations and using lci/console for cli commands.

## Requirements

- MODX needs to be installed
- SSH into your server or Terminal/CLI access

## Install steps

If you have not install composer do so now before proceeding

- [Install Composer on MODX Cloud](https://support.modx.com/hc/en-us/articles/221296007-Composer)
- [Install Composer](https://getcomposer.org/doc/00-intro.md)

**Recommended Directory, MODX core path**

1. CD to MODX_PATH/core

**Traditional MODX components path**

1. Go to MODX/core/components
2. ```mkdir orchestrator```
3. Then cd into the directory
4. Create an .env file and add the following values:
```
LCI_ORCHESTRATOR_BASE_PATH="core/components/orchestrator/vendor/"
LCI_ORCHESTRATOR_BASE_URL="core/components/orchestrator/vendor/"
LCI_ORCHESTRATOR_VENDOR_PATH="MODX_CORE_PATH/components/orchestrator/vendor/" 
```

**Required steps**

4. Optionally create a composer.json file, see below for example
5. Run ```composer install lci/orchestrator``` or ```composer require```
6. Then run command to install ```php vendor/bin/orchestrator orchestrator:install```
7. May also need to do: ```php vendor/bin/blend``` this will register the blend commands within orchestrator

### composer.json 

**Optional create your composer.json file, this will get the latest commit**
```json
{

  "require": {
       "lci/orchestrator": "dev-master"
   },
  "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/LippertComponents/Orchestrator"
        }
    ],

  "minimum-stability": "dev"
}
```

### Orchestrator will install in MODX

[See Install](src/database/migrations/InstallOrchestrator.php) for complete details

1. MODX namespace (orchestrator)  
Used to tie all system settings to the Orchestrator project.
2. Media source (orchestrator)  
The media source can be used for all extended projects allowing to create all elements as static. If the elements have 
been set to static for future updates those elements do not need to be updated via a Blend Migration. But if they are not 
static then each change to an element would need to be in noted the related update Blend Migration.
3. Plugin (requireComposerAutoloader) this will be loaded on the OnInitCulture event. Now all Snippets/Pluggins can use composer
autoloader.
4. System setting (orchestrator.vendor_path)  
Used in the requireComposerAutoloader plugin to map to the correct autoloader file


## @TODO 

 - [ ] Properly run composer.json scripts on install & update
 - [ ] Copy assets method
 - [ ] Tests
 - [ ] Helper Method to add in package path on blend->static
 
 
## Using Orchestrator in your package/extra

### composer.json 

An example project

```json
{
  "name": "lci/modxcore",
  "require": {
       "lci/orchestrator": "dev-master"
   },
  "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/LippertComponents/Orchestrator"
        }
    ],

  "minimum-stability": "dev"
}
```

## Your extended project directory structure

Assuming a PSR-4 PHP composer structure and then in the src directory add in elements for MODX elements as follows:
``` 
public/
src/
  database/
     migrations/
         InstallMyPackage.php
         UpdateMyPackage_v1_0_1.php
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

