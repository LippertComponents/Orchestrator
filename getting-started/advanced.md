---
layout: default
title: Advanced Setup
nav_order: 1
has_children: false
parent: Getting Started
---
# Advanced Setup

The advanced set up optional and only adds small benefit to the developer by allowing Orchestrator to listen to `composer` 
commands like install, update and remove. But the related package must be defined in the `core/config/lci_orchestrator_package.php` 
file. Orchestrator will then look for the related migrations for those projects to run with the composer commands.

The advanced setup can be followed from this [LocalOrchestrator Example](https://github.com/LippertComponents/LocalOrchestrator) 

## Note

Once you have completed the advanced example above you may need to pass an additional parameter to composer if you do not have 
MODX installed.
 
For example on local project that has PHP and composer set up but no active MODX installation. This will prevent the 
migrations from running and which would produce a fatal error since there is no MODX installed. 

Run appropriate:  
```composer install --no-scripts```  
or  
```composer update --no-scripts```


You can also call the scripts directly and verify that they work.

Then you can just run either:  
```composer run-script post-install-cmd```  
or  
```composer run-script post-update-cmd```
