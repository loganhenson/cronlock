<?php

namespace CronLock;

use Predis\Client;

class CronLock
{
    private $client;
    private $timeout;

    public function __construct(Client $client, $timeout = 300)
    {
        $this->client = $client;
        $this->timeout = $timeout;
    }

    /**
     * Checks if a lock exists, if not, creates it, sets the expire timeout and runs the job
     *
     * @param string $key
     * @param callable $f
     */
    public function cron($key, callable $f)
    {
        /**
         * If not already set
         */
        if ((bool) $this->client->setnx($key, time())) {
            set_time_limit(0);

            /**
             * Run the job
             */
            $f();

            /**
             * Expire the key after the job + timeout
             */
            $this->client->expire($key, $this->timeout);
        }
    }
}
