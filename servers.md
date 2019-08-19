---
layout: default
title: Servers & Clouds
nav_order: 9
has_children: false
---
# Changing Servers or Clouds? 

One issue with Orchestrator is that the `orchestrator.vendor_path` system setting has the full path and 
if it is incorrect it will prevent MODX from running, causing a fatal error.

If you do a MODXCloud snapshot or SQL dump of a server and then import that file into another server, you will need to 
update the `orchestrator.vendor_path` system setting. The following is an example of a SQL statement that needs to run, 
just set it to your correct path.

```sql
# Set path correctly for Orchestrator:
UPDATE `modx_system_settings`
SET
    `value` = '/paas/cXXXX/www/core/vendor/'
WHERE
    `key` = 'orchestrator.vendor_path';

```
