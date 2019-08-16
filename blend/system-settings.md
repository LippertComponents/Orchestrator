---
layout: default
title: System Settings
nav_order: 6
has_children: false
parent: Blend Migrations
---
# System Settings

System Settings can be created or updated with a migration. 

If you have not done so, generate a migration class. See the [Local](../local-development.md) or 
[Package](../package-development.md) development docs for more info.

Example:

```php
<?php
    protected $custom_system_settings = [
        'myPackage.navSiteStart' => [
            'area' => 'Nav',
            'type' => 'numberfield',
            'value' => 0 //'The resource ID to which to send to MenuBuilder for the main navigation menu'
        ],
        'myPackage.navLogoUrl' => [
            'area' => 'Nav',
            'type' => 'textfield',
            'value' => '/' //'The URL to send user to when clicking on the logo in the main menu'
        ],
        'myPackage.navLogoImage' => [
            'area' => 'Nav',
            'type' => 'textfield',
            'value' => '/assets/templates/logo.png',
            // 'The logo image url'
        ],
        'myPackage.navLogoAlt' => [
            'area' => 'Nav',
            'type' => 'textfield',
            'value' => 'My Project',
            // 'Alt for the navigation logo'
        ],
    ];
/**
 * Run the migrations.
 *
 * @return void
 */
public function up()
{
    /** @var \LCI\Blend\Blendable\SystemSetting $coreSchema */
    $coreSchema = $this->blender->getBlendableLoader()->getBlendableSystemSetting('link_tag_scheme');
    $coreSchema
        ->setSeedsDir($this->getSeedsDir())
        ->setCoreLinkTagScheme('abs');

    if ($coreSchema->blend(true)) {
        $this->blender->outSuccess('The link_tag_scheme system setting has been set');
    } else {
        $this->blender->outError('The link_tag_scheme system setting has been set');
        $this->blender->outError(print_r($coreSchema->getErrorMessages(), true), \LCI\Blend\Blender::VERBOSITY_DEBUG);
    }
    
    /** @var \LCI\Blend\Blendable\SystemSetting $coreFriendlyUrls */
    $coreFriendlyUrls = $this->blender->getBlendableLoader()->getBlendableSystemSetting('friendly_urls');
    $coreFriendlyUrls
        ->setSeedsDir($this->getSeedsDir())
        ->setCoreFriendlyUrls(true);

    if ($coreFriendlyUrls->blend(true)) {
        $this->blender->outSuccess('The friendly_urls system setting has been set');
    } else {
        $this->blender->outError('The friendly_urls system setting has been set');
        $this->blender->outError(print_r($coreFriendlyUrls->getErrorMessages(), true), \LCI\Blend\Blender::VERBOSITY_DEBUG);
    }


    ////////////
    // More advanced:

    // MODX Namespace to attach system settings to
    $myPackageNamespace = $this->modx->getObject('modNamespace', 'myPackage');
    if (!$myPackageNamespace) {
        /** @var \modNamespace $myPackageNamespace */
        $myPackageNamespace = $this->modx->newObject('modNamespace');
        $myPackageNamespace->set('name', 'myPackage');
        $myPackageNamespace->set('path', '{core_path}vendor/my/package/src/');
        $myPackageNamespace->set('assets_path', '{assets_path}components/my/package/');

        if ($myPackageNamespace->save()) {
            $this->blender->outSuccess('The modNamespace myPackage has been created');
        } else {
            $this->blender->out('The modNamespace myPackage was not created', true);
        }
    }

    foreach ($this->custom_system_settings as $setting => $details) {
        /** @var LCI\Blend\Blendable\SystemSetting $mySystemSetting */
        $mySystemSetting = $this->blender->getBlendableLoader()->getBlendableSystemSetting($setting);

        $mySystemSetting->setSeedsDir($this->getSeedsDir());

        $mySystemSetting
            ->setFieldNamespace('myPackage')
            ->setFieldArea($details['area'])
            ->setFieldValue($details['value'])
            ->setFieldXType($details['type']);

        // The blend() method will create a back/down data before saving to allow for easy revert with the revertBlend method
        if ($mySystemSetting->blend(true)) {
            $this->blender->out($mySystemSetting->getFieldName() . ' was saved correctly');

        } else {
            //error
            $this->blender->outError($mySystemSetting->getFieldName() . ' did not save correctly ');
            $this->blender->outError(print_r($mySystemSetting->getErrorMessages(), true), \LCI\Blend\Blender::VERBOSITY_DEBUG);
        }
    }
}

/**
 * Reverse the migrations.
 *
 * @return void
 */
public function down()
{
    /** @var \LCI\Blend\Blendable\SystemSetting $coreSchema */
    $coreSchema = $this->blender->getBlendableLoader()->getBlendableSystemSetting('link_tag_scheme');
    $coreSchema->setSeedsDir($this->getSeedsDir());

    if ($coreSchema->revertBlend()) {
        $this->blender->out($coreSchema->getFieldName() . ' was reverted correctly');
    }

    /** @var \LCI\Blend\Blendable\SystemSetting $coreFriendlyUrls */
    $coreFriendlyUrls = $this->blender->getBlendableLoader()->getBlendableSystemSetting('friendly_urls');
    $coreFriendlyUrls->setSeedsDir($this->getSeedsDir());
    
    if ($coreFriendlyUrls->revertBlend()) {
        $this->blender->out($coreFriendlyUrls->getFieldName() . ' was reverted correctly');
    }

    // More advanced:
    // MODX Namespace to attach system settings to
    $myPackageNamespace = $this->modx->getObject('modNamespace', 'myPackage');
    if (is_object($myPackageNamespace)) {
        $myPackageNamespace->remove();
    }

    foreach ($this->custom_system_settings as $setting => $details) {
        /** @var LCI\Blend\Blendable\SystemSetting $mySystemSetting */
        $mySystemSetting = $this->blender->getBlendableLoader()->getBlendableSystemSetting($setting);

        $mySystemSetting->setSeedsDir($this->getSeedsDir());

        if ($mySystemSetting->revertBlend()) {
            $this->blender->out($mySystemSetting->getFieldName() . ' was reverted correctly');

        } else {
            //error
            $this->blender->outError($mySystemSetting->getFieldName() . ' did not save correctly ');
            $this->blender->outError(print_r($mySystemSetting->getErrorMessages(), true), \LCI\Blend\Blender::VERBOSITY_DEBUG);
        }
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
