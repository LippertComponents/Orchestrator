# Getting Started

## Requirements

- A working installation of MODX version ^2.6
- Command line access, local or SSH into your server. [Terminal for macOS](https://support.apple.com/guide/terminal/welcome/mac), [Git BASH for Windows](https://gitforwindows.org/)
- [Install Composer](https://getcomposer.org/doc/00-intro.md), using MODX Cloud? [Install Composer on MODX Cloud](https://support.modx.com/hc/en-us/articles/221296007-Composer)
- PHP 7

## Basic installation steps

If you have not installed MODX and composer do so now before proceeding.

> The basic step follows the directory structure of a basic install of MODX. All examples will be based on running MODX on a MODXCloud instance.  

1. Open up your command line tool into the MODX core directory like so:  
    ```  
    cd /www/core/  
    ```
2. Create an empty .env file, this can be used for configurations later on  
    ```
    touch .env   
    ```
3. Run the composer command to install  
    ``` 
    composer require lci/orchestrator
    ```
4. Now run the installation command  
    ```
    php vendor/bin/orchestrator orchestrator:install
    ```

That's it! Orchestrator is now installed, to verify run the command:
``` 
php vendor/bin/orchestrator
```
And you should see something like this:
![Orchestrator installed!](https://raw.githubusercontent.com/LippertComponents/Orchestrator/gh-pages/images/successful-installed.png)


## What does Orchestrator install into MODX?

[See Install](https://github.com/LippertComponents/Orchestrator/blob/master/src/database/migrations/InstallOrchestrator.php) for complete details

1. [Blend](https://github.com/LippertComponents/Blend) package
2. MODX namespace `orchestrator`  
Used to tie all system settings to the Orchestrator project.
3. Media source `orchestrator`  
The media source can be used for all extended projects allowing to create all elements as static. If the elements have 
been set to static for future updates those elements do not need to be updated via a Blend Migration. But if they are not 
static then each change to an element would need to be in noted the related update Blend Migration.
4. Plugin `requireComposerAutoloader` this will be loaded on the MODX `OnInitCulture` event. Now all Snippets/Plugins can use the composer
autoloader.
5. System setting `orchestrator.vendor_path`  
Used in the requireComposerAutoloader plugin to map to the correct autoloader file
