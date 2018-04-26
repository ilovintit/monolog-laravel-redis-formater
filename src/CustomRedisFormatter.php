<?php

namespace Iit\RedisMonolog;

use Monolog\Logger;

class CustomRedisFormatter
{
    /**
     * Customize the given logger instance.
     *
     * @param  Logger $logger
     * @return void
     */
    public function __invoke($logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter(new RedisFormatter());
        }
    }
}