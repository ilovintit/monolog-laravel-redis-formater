<?php

namespace Iit\RedisMonolog;

use Monolog\Handler\RedisHandler;
use Monolog\Logger;
use Predis\Client;

class CustomRedisLogger
{
    /**
     * Create a custom Monolog instance.
     *
     * @param  array $config
     * @return \Monolog\Logger
     */
    public function __invoke(array $config)
    {
        return new Logger('redis-sls', [(new RedisHandler(new Client([
            'scheme' => 'tcp',
            'host' => $config['redis_host'],
            'port' => $config['redis_port'],
            'password' => $config['redis_password']
        ]), $config['redis_monolog_name'] ? $config['redis_monolog_name'] : 'RedisMonolog'))
            ->setFormatter(new RedisFormatter())]);
    }
}