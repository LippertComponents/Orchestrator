---
layout: default
title: Snippets
nav_order: 5
has_children: false
parent: Blend Migrations
---
# Snippets

Snippet can be created or updated with a migration. 

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
    /** @var \LCI\Blend\Blendable\Snippet $mySnippet */
    $mySnippet = $this->blender->getBlendableLoader()->getBlendableSnippet('mySnippet');
    
    // replace my/package with your Composer Package name as listed on your composer.json file
    // Then the full relative file path of your file
    $mySnippet
        ->setSeedsDir($this->getSeedsDir())// This is needed to set the down() data
        ->setFieldDescription('This is my local test snippet, note this is limited to 255 or something')
        ->setFieldCategory('My Site=>Snippets')
        //  file path                                          Media Source name, assuming you do not change the default MODX file system media source:
        ->setAsStatic('core/components/local/elements/snippets/mySnippet.tpl', 'filesystem');
        // OR see the Local and Package Development documents for more info
        //->setAsStatic('my/project/src/elements/snippets/mySnippet.tpl','orchestrator');
    
    // The blend() method will create a back/down data before saving to allow for easy revert with the revertBlend method
    if ($mySnippet->blend(true)) {
        $this->blender->out($mySnippet->getFieldName().' was saved correctly');
    
    } else {
        //error
        $this->blender->outError($mySnippet->getFieldName().' did not save correctly ');
        $this->blender->outError(print_r($mySnippet->getErrorMessages(), true), \LCI\Blend\Blender::VERBOSITY_DEBUG);
    }
}

/**
 * Reverse the migrations.
 *
 * @return void
 */
public function down()
{
    /** @var \LCI\Blend\Blendable\Snippet $mySnippet */
    $mySnippet = $this->blender->getBlendableLoader()->getBlendableSnippet('mySnippet');
    $mySnippet->setSeedsDir($this->getSeedsDir());// This is needed to retrieve the down data
    
    if ( $mySnippet->revertBlend() ) {
        $this->blender->out($mySnippet->getFieldName().' snippet has been reverted to '.$this->getSeedsDir());
    
    } else {
        $this->blender->outError($mySnippet->getFieldName().' snippet was not reverted');
    }
}
```

## Snippet seeds

Create snippet seeds for work that you have completed within the MODX Manager and you wish to export one more snippets
to another instance.

### Simple example

Run:
```
cd /www/core/
php vendor/bin/orchestrator blend:seed --object snippet
```  
You will then be prompted for enter in a comma separated list of snippet names or IDs to include.

A migration file with a timestamp will be created and then a new directory for all of the seed data:
```
core/components/blend/database/migrations/m2019_08_16_180000_Snippet.php
core/components/blend/database/seeds/m2019_08_16_180000_Snippet/elements
```

### Select Snippets

Pass snippet IDs as an option for the command as a comma separated list of IDs. Example seed snippets with the IDs 2 and 3.
```
cd /www/core/
php vendor/bin/orchestrator blend:seed  --object snippet --id 2,3
```

Give your migration a custom name, maybe for a version or bug number:  
```
cd /www/core/
php vendor/bin/orchestrator blend:seed  --object snippet --name Issue1234
```

### Customize Snippets

If you want to customize the content on export you can write a 
[plugin](https://docs.modx.com/revolution/2.x/developing-in-modx/basic-development/plugins) using the following events:

 - OnBlendBeforeSave
 - OnBlendAfterSave
 - OnBlendSeed
 - OnBlendLoadRelatedData
