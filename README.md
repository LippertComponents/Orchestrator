# Orchestrator

Allowing developers to use MODX along with composer managed packages utilizing Blend Migrations and using lci/console for cli commands.

## Install steps

If you have not install composer do so now before proceeding

- [Install Composer on MODX Cloud](https://support.modx.com/hc/en-us/articles/221296007-Composer)
- [Install Composer](https://getcomposer.org/doc/00-intro.md)

## Requirements

- MODX needs to be installed

**Traditional MODX components path**

1. Go to MODX/core/components
2. ```mkdir orchestrator```
3. Then cd into the directory
4. Create a composer.json file and put in the contents as mentioned below. This is temporary until released on Packagist.org.
4. Run ```composer install lci/orchestrator``` or ```composer require```
5. Then run command to install ```php vendor/bin/orchestrator orchestrator:install```
6. May also need to do: ```php vendor/bin/blend``` this will register the blend commands within orchestrator

## composer.json 

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

## @TODO 

 - [ ] Properly run composer.json scripts on install & update
 - [ ] Tests
 