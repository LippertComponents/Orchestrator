# Git Workflow

Use Orchestrator to go from dev to production for your MODX websites. To add any MODX Elements like Chunks and Templates, 
create these via Blend Migrations that can be saved to your git repo and then simply ran on the remaining servers to quickly 
and accurately deploy your updates.

## Suggested git setup

1. Include all MODX files in git minus a few config files and cache. This helps a lot when creating any PHP code as you can use your IDE to help auto complete.
Note Orchestrator does not update MODX versions, so this is still a manual process or via MODXCloud to update. 
2. Optionally include all `core/vendor` files in git. This speeds up deployment but make sure you have the same PHP version on all
servers or set your deployment [PHP version](https://getcomposer.org/doc/06-config.md#platform) in `core/composer.json`
3. Create an .ignore file, basic example below
4. Now create a branch for every server that you wish to have, for example `dev` for development and `master` for production.
Another common branch would be `staging` for a middle staging server to show for stakeholder approval before deployment to 
production.

## .gitignore basic example

```ignore
# modx
/assets/components/phpthumbof/cache/
/assets/cache/
/connectors/config.core.php
/core/cache/
/core/config/config.inc.php
/core/config/lci_console_env.php
/core/packages/

!/core/vendor/bin/
!/core/vendor/**/bin/
/core/vendor/lci/console/src/cache/env.php
/config.core.php
/manager/config.core.php

# Local project
/core/components/blend/database/history/

```

## Deploy on a server

Orchestrator has a deploy command that will check your related config files to install or update any defined MODX extras 
as well as run your local project migrations and any new migrations for packages.

```
cd /www/
git pull
 ...
cd /www/core
php vendor/bin/orchestrator deploy
```

## How is Orchestrator different from Gitify?

[Gitify](https://docs.modmore.com/en/Open_Source/Gitify/Installation/index.html) is also a good solution for saving your
MODX content and elements to files to allow a git workflow. One advantage to Blend Migrations is that it abstracts IDs so
you can have many developers working on different migration scripts without having any ID collisions or overwrites. Another
is you can create and share scripts that can be reused for any website. 
