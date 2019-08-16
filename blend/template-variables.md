# Template Variables

Template Variables can be created or updated with a migration. 

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
    /////////////
    // Text example:
    /** @var \LCI\Blend\Blendable\TemplateVariable $myTextTV */
    $myTextTV = $this->blender->getBlendableLoader()->getBlendableTemplateVariable('buttonText');
    $myTextTV->setSeedsDir($this->getSeedsDir());

    $myTextTV
        ->setSeedsDir($this->getSeedsDir())
        ->setFieldCategory('My Project=>Large Box')
        ->setFieldCaption('Button text')
        ->setFieldType('text')
        ->setFieldRank(2);

    if ($myTextTV->blend(true)) {
        $this->blender->out($myTextTV->getFieldName() . ' was saved correctly');

    } else {
        //error
        $this->blender->outError($myTextTV->getFieldName() . ' did not save correctly ');
        $this->blender->outError(print_r($myTextTV->getErrorMessages(), true), \LCI\Blend\Blender::VERBOSITY_DEBUG);
    }

    /////////////
    // Listbox example:
    /** @var \LCI\Blend\Blendable\TemplateVariable $myListBox */
    $myListBox = $this->blender->getBlendableLoader()->getBlendableTemplateVariable('sectionLayout');
    $myListBox->setSeedsDir($this->getSeedsDir());
    
    $myListBox
        ->setSeedsDir($this->getSeedsDir())
        ->setFieldCategory('My Project=>Large Box')
        ->setFieldCaption('Content alignment')
        ->setFieldDefaultText('default')
        ->setFieldType('listbox')
        ->setFieldRank(1);

    /** @var \LCI\Blend\Helpers\TVInput\OptionValues $inputOptionValues */
    $inputOptionValues = $myListBox->makeInputOptionValues();
    $inputOptionValues
        ->setOption('Default', 'default')
        ->setOption('Left', 'left')
        ->setOption('Right', 'right');

    if ($myListBox->blend(true)) {
        $this->blender->out($myListBox->getFieldName() . ' was saved correctly');

    } else {
        //error
        $this->blender->outError($myListBox->getFieldName() . ' did not save correctly ');
        $this->blender->outError(print_r($myListBox->getErrorMessages(), true), \LCI\Blend\Blender::VERBOSITY_DEBUG);
    }

    /////////////
    // MIGX example, note this requires MIGX to be installed to actually work
    /** @var \LCI\Blend\Blendable\TemplateVariable $migxTV */
    $migxTV = $this->blender->getBlendableLoader()->getBlendableTemplateVariable('videoBlockCarousel');
    $migxTV->setSeedsDir($this->getSeedsDir());

    $migxTV->setSeedsDir($this->getSeedsDir())
        ->setFieldCategory('My Project=>Video Block Carousel')
        ->setFieldCaption('Video Block Carousel')
        ->setFieldDescription('Create multiple video block sections that live inside of a carousel/slider.')
        ->setMediaSource('Backgrounds')
        ->setFieldRank(1);

    $migxHelper = $migxTV->getMIGXInputPropertyHelper();

    /** @var \LCI\Blend\Helpers\MIGX\Tab $tab */
    $tab = $migxHelper->addFormTab('Info');

    $tab->makeField('title')
        ->setCaption('Title')
        ->setShowInGrid(true)
        ->setGridSortable(true)
        ->setGridHeader('Title')
        ->setGridWidth(1);

    $tab->makeField('backgroundImage')
        ->setCaption('Background Image')
        //Note this must be an existing TV or create it via the migration:
        ->setInputTemplateVariableName('backgroundImage')
    ;

    $tab->makeField('videoURL')
        ->setCaption('Video URL')
        //Note this must be an existing TV or create it via the migration:
        ->setInputTemplateVariableName('videoBlockVideoURL')
    ;

    $migxTV->setFieldInputProperties($migxHelper->getInputProperties());

    if ($migxTV->blend(true)) {
        $this->blender->out($migxTV->getFieldName() . ' was saved correctly');

    } else {
        //error
        $this->blender->outError($migxTV->getFieldName() . ' did not save correctly ');
        $this->blender->outError(print_r($migxTV->getErrorMessages(), true), \LCI\Blend\Blender::VERBOSITY_DEBUG);
    }
}

/**
 * Reverse the migrations.
 *
 * @return void
 */
public function down()
{
    /** @var \LCI\Blend\Blendable\TemplateVariable $myTextTV */
    $myTextTV = $this->blender->getBlendableLoader()->getBlendableTemplateVariable('buttonText');
    $myTextTV->setSeedsDir($this->getSeedsDir());

    if ($myTextTV->revertBlend()) {
        $this->blender->out($myTextTV->getFieldName() . ' was reverted correctly');

    } else {
        //error
        $this->blender->outError($myTextTV->getFieldName() . ' did not revert correctly ');
        $this->blender->outError(print_r($myTextTV->getErrorMessages(), true), \LCI\Blend\Blender::VERBOSITY_DEBUG);
    }

    /** @var \LCI\Blend\Blendable\TemplateVariable $myListBox */
    $myListBox = $this->blender->getBlendableLoader()->getBlendableTemplateVariable('sectionLayout');
    $myListBox->setSeedsDir($this->getSeedsDir());

    if ($myListBox->revertBlend()) {
        $this->blender->out($myListBox->getFieldName() . ' was reverted correctly');

    } else {
        //error
        $this->blender->outError($myListBox->getFieldName() . ' did not revert correctly ');
        $this->blender->outError(print_r($myListBox->getErrorMessages(), true), \LCI\Blend\Blender::VERBOSITY_DEBUG);
    }


    /** @var \LCI\Blend\Blendable\TemplateVariable $migxTV */
    $migxTV = $this->blender->getBlendableLoader()->getBlendableTemplateVariable('videoBlockCarousel');
    $migxTV->setSeedsDir($this->getSeedsDir());

    if ($migxTV->revertBlend()) {
        $this->blender->out($migxTV->getFieldName() . ' was reverted correctly');

    } else {
        //error
        $this->blender->outError($migxTV->getFieldName() . ' did not revert correctly ');
        $this->blender->outError(print_r($migxTV->getErrorMessages(), true), \LCI\Blend\Blender::VERBOSITY_DEBUG);
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
