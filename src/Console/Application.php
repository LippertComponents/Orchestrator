<?php
/**
 * Created by PhpStorm.
 * User: joshgulledge
 * Date: 9/3/18
 * Time: 1:48 PM
 */

namespace LCI\MODX\Orchestrator\Console;


class Application extends \LCI\MODX\Console\Application
{
    // @see http://patorjk.com/software/taag/#p=display&f=Slant&t=Console
    protected static $logo = __DIR__ . '/art/orchestrator.txt';

    protected static $name = 'Orchestrator Console';

    protected static $version = '1.0.0 beta3';
}