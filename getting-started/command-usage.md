---
layout: default
title: Command Usage
nav_order: 2
has_children: false
parent: Getting Started
---
# Command Usage 

Orchestrator is using Symfony Console to create the command line interface. Read over the [Built-in Commands](https://symfony.com/doc/current/components/console/usage.html#built-in-commands) 
to get a basic understand of what options you can pass.

What are commands?

Think of commands as a single program that can do one or two things. And that command can take arguments and options. To 
see a full list of commands simple do:

```
cd /www/core/
php vendor/bin/orchestrator
// Or 
php vendor/bin/orchestrator list
```

## Command Help

Every command will have some help screen that will define better how to use. Just add the `--help` option to the command 
like so:

```
cd /www/core/
php vendor/bin/orchestrator orchestrator:deploy --help
// Or shortcut:
php vendor/bin/orchestrator orchestrator:deploy -h
```
