---
layout: default
title: Templates
nav_order: 7
has_children: false
parent: Blend Migrations
---
# Template

Template can be created or updated with a migration. 

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
    $data = [
            'categories' => 'My Project=>Template',
            'description' => 'Basic template',
            'tvs' => [
                'buttonText' => 2,
                'sectionLayout' => 1,
                'videoBlockCarousel' => 1,
            ]
        ];
    /** @var \LCI\Blend\Blendable\Template $myTemplate */
    $myTemplate = $this->blender->getBlendableLoader()->getBlendableTemplate('myTemplate');
    $myTemplate->setSeedsDir($this->getSeedsDir());
    
    $myTemplate
        ->setSeedsDir($this->getSeedsDir())
        ->setFieldCategory($data['categories'])
        ->setFieldDescription($data['description'])
        ->setAsStatic('core/components/local/elements/templates/myTemplate.tpl', 'filesystem');
        // OR see the Local and Package Development documents for more info
        //->setAsStatic('my/project/src/elements/templates/myTemplate.tpl','orchestrator');
    
    // These TV must already exist if not then create them, see Template Variables
    foreach ($data['tvs'] as $tvName => $rank) {
        $myTemplate->attachTemplateVariable($tvName, $rank);
    }

    if ($myTemplate->blend(true)) {
        $this->blender->out($myTemplate->getFieldName() . ' was saved correctly');

    } else {
        //error
        $this->blender->outError($myTemplate->getFieldName() . ' did not save correctly ');
        $this->blender->outError(print_r($myTemplate->getErrorMessages(), true), \LCI\Blend\Blender::VERBOSITY_DEBUG);
    }
}

/**
 * Reverse the migrations.
 *
 * @return void
 */
public function down()
{
    /** @var \LCI\Blend\Blendable\Template $myTemplate */
    $myTemplate = $this->blender->getBlendableLoader()->getBlendableTemplate('myTemplate');
    $myTemplate->setSeedsDir($this->getSeedsDir());
    
    if ($myTemplate->revertBlend()) {
        $this->blender->out($myTemplate->getFieldName() . ' was reverted correctly');

    } else {
        //error
        $this->blender->outError($myTemplate->getFieldName() . ' did not revert correctly ');
        $this->blender->outError(print_r($myTemplate->getErrorMessages(), true), \LCI\Blend\Blender::VERBOSITY_DEBUG);
    }

}
```

## Template seeds

Create template seeds for work that you have completed within the MODX Manager and you wish to export one more templates
to another instance.

### Simple example

Run:
```
cd /www/core/
php vendor/bin/orchestrator blend:seed --object template
```  
You will then be prompted for enter in a comma separated list of template names or IDs to include.

A migration file with a timestamp will be created and then a new directory for all of the seed data:
```
core/components/blend/database/migrations/m2019_08_16_180000_Template.php
core/components/blend/database/seeds/m2019_08_16_180000_Template/elements
```

### Select Templates

Pass template IDs as an option for the command as a comma separated list of IDs. Example seed templates with the IDs 2 and 3.
```
cd /www/core/
php vendor/bin/orchestrator blend:seed  --object template --id 2,3
```

Give your migration a custom name, maybe for a version or bug number:  
```
cd /www/core/
php vendor/bin/orchestrator blend:seed  --object template --name Issue1234
```

### Customize Templates

If you want to customize the content on export you can write a 
[plugin](https://docs.modx.com/revolution/2.x/developing-in-modx/basic-development/plugins) using the following events:

 - OnBlendBeforeSave
 - OnBlendAfterSave
 - OnBlendSeed
 - OnBlendLoadRelatedData
