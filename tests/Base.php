<?php
//declare(strict_types=1);

namespace LCI\MODX\Orchestrator\Tests;

use LCI\MODX\Console\Console;
use modX;
use PHPUnit\Framework\TestCase;

class Base extends TestCase
{
    /** @var Console */
    protected $console;

    /**
     * @var Console
     */
    public static $fixture = null;

    /**
     * Setup static properties when loading the test cases.
     */
    public static function setUpBeforeClass()
    {

    }

    /**
     * This method is called after the last test of this test class is run.
     */
    public static function tearDownAfterClass()
    {

    }

    /**
     * @param bool $new
     * @return Console
     */
    public static function getInstance($new = false)
    {
        if ($new || !is_object(self::$fixture)) {
            self::$fixture = new Console();
        }

        return self::$fixture;
    }

    /**
     * Set up the modX fixture for each test case.
     */
    protected function setUp()
    {
        $this->console = self::getInstance();
    }

    /**
     * Tear down the xPDO(modx) fixture after each test case.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->console = null;
    }
}
