---
layout: default
title: Plugins
nav_order: 2
has_children: false
parent: Blend
---
# Plugins

Plugin can be created or updated with a migration. 

If you have not done so, generate a migration class. See the [Local](../local-development.md) or 
[Package](../package-development.md) development docs for more info.

Example:

```php
<?php
    
/**
 * Run the migrations.
 *
 * @return void
 */
public function up()
{
    /** @var \LCI\Blend\Blendable\Plugin $myPlugin */
    $myPlugin = $this->blender->getBlendableLoader()->getBlendablePlugin('myPlugin');
    
    // replace my/package with your Composer Package name as listed on your composer.json file
    // Then the full relative file path of your file
    $myPlugin
        ->setSeedsDir($this->getSeedsDir())// This is needed to set the down() data
        ->setFieldDescription('This is my local test plugin, note this is limited to 255 or something')
        ->setFieldCategory('My Site=>Plugins')
        ->attachOnEvent('OnDocFormSave')
        ->attachOnEvent('OnDocPublished')
        ->attachOnEvent('OnDocUnPublished')
        ->attachOnEvent('OnDocFormDelete')
        ->attachOnEvent('OnResourceAutoPublish')
        //  file path                                          Media Source name, assuming you do not change the default MODX file system media source:
        ->setAsStatic('core/components/local/elements/plugins/myPlugin.tpl', 'filesystem');
        // OR see the Local and Package Development documents for more info
        //->setAsStatic('my/project/src/elements/plugins/myPlugin.tpl','orchestrator');
    
    // The blend() method will create a back/down data before saving to allow for easy revert with the revertBlend method
    if ($myPlugin->blend(true)) {
        $this->blender->out($myPlugin->getFieldName().' was saved correctly');
    
    } else {
        //error
        $this->blender->outError($myPlugin->getFieldName().' did not save correctly ');
        $this->blender->outError(print_r($myPlugin->getErrorMessages(), true), \LCI\Blend\Blender::VERBOSITY_DEBUG);
    }
}

/**
 * Reverse the migrations.
 *
 * @return void
 */
public function down()
{
    /** @var \LCI\Blend\Blendable\Plugin $myPlugin */
    $myPlugin = $this->blender->getBlendableLoader()->getBlendablePlugin('myPlugin');
    $myPlugin->setSeedsDir($this->getSeedsDir());// This is needed to retrieve the down data
    
    if ( $myPlugin->revertBlend() ) {
        $this->blender->out($myPlugin->getFieldName().' plugin has been reverted to '.$this->getSeedsDir());
    
    } else {
        $this->blender->outError($myPlugin->getFieldName().' plugin was not reverted');
    }
}
```

## Plugin seeds

Create plugin seeds for work that you have completed within the MODX Manager and you wish to export one more plugins
to another instance.

### Simple example

Run:
```
cd /www/core/
php vendor/bin/orchestrator blend:seed --object plugin
```  
You will then be prompted for enter in a comma separated list of plugin names or IDs to include.

A migration file with a timestamp will be created and then a new directory for all of the seed data:
```
core/components/blend/database/migrations/m2019_08_16_180000_Plugin.php
core/components/blend/database/seeds/m2019_08_16_180000_Plugin/elements
```

### Select Plugins

Pass plugin IDs as an option for the command as a comma separated list of IDs. Example seed plugins with the IDs 2 and 3.
```
cd /www/core/
php vendor/bin/orchestrator blend:seed  --object plugin --id 2,3
```

Give your migration a custom name, maybe for a version or bug number:  
```
cd /www/core/
php vendor/bin/orchestrator blend:seed  --object plugin --name Issue1234
```

### Customize Plugins

If you want to customize the content on export you can write a 
[plugin](https://docs.modx.com/revolution/2.x/developing-in-modx/basic-development/plugins) using the following events:

 - OnBlendBeforeSave
 - OnBlendAfterSave
 - OnBlendSeed
 - OnBlendLoadRelatedData
