<?php

use CronLock\CronLock;
use Predis\Client;

class CronLockTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $client = new Client();
        $client->flushall();
    }

    public function testOnlyRunsOnceWithinTimeout()
    {
        /**
         * 2 seconds timeout
         */
        $CronLock = new CronLock(new Client(), 2);

        $count = 0;

        $key = __CLASS__ . __METHOD__;

        $CronLock->cron($key, function () use (&$count) {
            $count++;
        });

        /**
         * 1 second is still within timeout
         */
        sleep(1);

        $CronLock->cron($key, function () use (&$count) {
            $count++;
        });

        $this->assertEquals(1, $count);
    }

    public function testRunsTwiceWithoutTimeout()
    {
        /**
         * 2 seconds timeout
         */
        $CronLock = new CronLock(new Client(), 2);

        $count = 0;

        $key = __CLASS__ . __METHOD__;

        $CronLock->cron($key, function () use (&$count) {
            $count++;
        });

        /**
         * 2 seconds is out of timeout
         */
        sleep(2);

        $CronLock->cron($key, function () use (&$count) {
            $count++;
        });

        $this->assertEquals(2, $count);
    }

    protected function tearDown()
    {
        $client = new Client();
        $client->flushall();
    }
}
