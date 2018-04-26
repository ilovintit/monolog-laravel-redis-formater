<?php

namespace Iit\RedisMonolog;

use Monolog\Handler\RedisHandler;
use Monolog\Logger;
use Predis\Client;

class CustomRedisLogger
{
    /**
     * Customize the given logger instance.
     *
     * @param  Logger $logger
     * @return void
     */
    public function __invoke($logger)
    {
        $logger->setHandlers([(new RedisHandler(new Client([
            'scheme' => 'tcp',
            'host' => env('REDIS_MONOLOG_HOST', env('REDIS_HOST', '127.0.0.1')),
            'port' => env('REDIS_MONOLOG_PORT', env('REDIS_PORT', 6379)),
            'password' => env('REDIS_MONOLOG_PASSWORD', env('REDIS_PASSWORD', null))
        ]), env('REDIS_MONOLOG_NAME', 'RedisMonolog')))->setFormatter(new RedisFormatter())]);

    }
}