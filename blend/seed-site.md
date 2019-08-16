---
layout: default
title: Seed Site
nav_order: 4
has_children: false
parent: Blend Migrations
---
# Seed site

Blend can create a complete site seed for your website with all Elements, Resources and System Settings. A seed is a migration
that can then be ran on another MODX instance to import/create your data.

Create a Migration and seeds for all elements, resources and system settings:
```
cd /www/core
php php vendor/bin/orchestrator blend:seed --object site --name InitSite
```

Same as above, but with short options
```
cd /www/core
php php vendor/bin/orchestrator blend:seed -o a -N InitSite
```

The above commands would then create a migration file with a date stamp on it like `core/components/blend/database/migrations/m2019_08_16_180000_InitSite.php`.
And there would also be the related data files in the `core/components/blend/database/seeds/` directory.
