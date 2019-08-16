# Resource Migration & Seeds

Resources can be created or updated with a migration. To use the this feature all resources need an alias and you should 
be using `friendly_alias_urls`.


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
    /** @var \LCI\Blend\Blendable\Resource $blendableResource */
    $blendableResource = $this->blender->getBlendableLoader()->getBlendableResource('some-resource-alias');
    $blendableResource
        ->setSeedsDir($this->getSeedsDir())
        ->setFieldContent('Content, can put in HTML here')
        ->setFieldDescription('This is the description field, it is short like 191 char')
        ->setFieldLongtitle('Really long title goes here... ')
        ->setFieldPagetitle('Page Title')
        ->setFieldTemplate('Some Template')
        ->setFieldParentFromAlias('parent-alias', 'web')
        ->setTVValueResourceIDFromAlias('resourceListTV', $this->parent_alias, 'web')
        ->setTVValue('richTextTV', '<h2>Children, this is only a test</h2>')
        ->setTVValue('textTV', 'A single line value goes here')
        ->setTVValue('textAreaTV', 'Lots of lines can go here ' . PHP_EOL . 'Line 2');

    // The blend() method will create a back/down data before saving to allow for easy revert with the revertBlend method
    if ($blendableResource->blend(true)) {
        $this->blender->out($blendableResource->getFieldPagetitle().' was saved correctly');
    
    } else {
        //error
        $this->blender->outError($blendableResource->getFieldPagetitle().' did not save correctly ');
        $this->blender->outError(print_r($blendableResource->getErrorMessages(), true), \LCI\Blend\Blender::VERBOSITY_DEBUG);
    }
}

/**
 * Reverse the migrations.
 *
 * @return void
 */
public function down()
{
    /** @var \LCI\Blend\Blendable\Resource $blendableResource */
    $blendableResource = $this->blender->getBlendableLoader()->getBlendableResource('some-resource-alias');
    $blendableResource->setSeedsDir($this->getSeedsDir());// This is needed to retrieve the down data
    
    if ( $blendableResource->revertBlend() ) {
        $this->blender->out($blendableResource->getFieldPagetitle().' resource has been reverted to '.$this->getSeedsDir());
    
    } else {
        $this->blender->outError($blendableResource->getFieldPagetitle().' resource was not reverted');
    }
}
```

## Resource seeds

Create resource seeds for work that you have completed within the MODX Manager and you wish to export one more resources
to another instance.

### Simple example

Run:
```
cd /www/core/
php vendor/bin/orchestrator blend:seed
```  
You will then be prompted for enter in a comma separated list of resource IDs to include.

A migration file with a timestamp will be created and then a new directory for all of the seed data:
```
core/components/blend/database/migrations/m2019_08_16_180000_Resource.php
core/components/blend/database/seeds/m2019_08_16_180000_Resource/resources
```

### Select Resources

Pass resource IDs as an option for the command as a comma separated list of IDs. Example seed resources with the IDs 2 and 3.
```
cd /www/core/
php vendor/bin/orchestrator blend:seed --id 2,3
```

Only do resources that have been created or modified since 2019-01-01  
```
cd /www/core/
php vendor/bin/orchestrator blend:seed --date 2019-01-01
```

Give your migration a custom name, maybe for a version or bug number:  
```
cd /www/core/
php vendor/bin/orchestrator blend:seed --date 2018-01-01 --name Issue1234
```

### Customize Resources

If you want to customize the content on export you can write a 
[plugin](https://docs.modx.com/revolution/2.x/developing-in-modx/basic-development/plugins) using the following events:

 - OnBlendBeforeSave
 - OnBlendAfterSave
 - OnBlendSeed
 - OnBlendLoadRelatedData
