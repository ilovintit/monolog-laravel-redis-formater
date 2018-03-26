<?php

namespace Iit\RedisMonolog;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Monolog\Formatter\FormatterInterface;
use Webpatser\Uuid\Uuid;

/**
 * Class RedisFormatter
 * @package app\Extend
 */
class RedisFormatter implements FormatterInterface
{
    public static $sequence = 0;

    public static $logId;

    protected static $processId;

    public function __construct()
    {
        try {
            self::$logId = Uuid::generate()->string;
        } catch (\Exception $e) {
            self::$logId = null;
        }
    }

    /**
     * Formats a log record.
     *
     * @param  array $record A record to format
     * @return mixed The formatted record
     * @throws \Exception
     */
    public function format(array $record)
    {
        $baseFormat = [
            'sequence' => self::$sequence,
            'logId' => self::$logId,
            'processId' => self::processId(),
            'timestamp' => $record['datetime']->format('Y-m-d H:i:s.u'),
            'timezone' => $record['datetime']->getTimezone()->getName(),
            'microTime' => microtime(true),
            'message' => $record['message'],
            'type' => $record['level_name'],
            'channel' => $record['channel'],
            'context' => $this->getContext($record, 'context'),
            'extra' => $this->getContext($record, 'extra'),
        ];
        $exception = isset($record['context']['exception']) ? $record['context']['exception'] : null;
        if ($exception instanceof \Exception) {
            $baseFormat['exception'] = $exception->__toString();
        }
        self::$sequence += 1;
        return json_encode($baseFormat);
    }

    /**
     * Formats a set of log records.
     *
     * @param  array $records A set of records to format
     * @return mixed The formatted set of records
     * @throws \Exception
     */
    public function formatBatch(array $records)
    {
        $message = [];
        foreach ($records as $record) {
            $message[] = $this->format($record);
        }
        return $message;
    }

    /**
     * @param $record
     * @param string $key
     * @return string
     */

    protected function getContext($record, $key = 'context')
    {
        if (!isset($record[$key])) {
            return "";
        }
        $context = $record[$key];
        if (is_array($context)) {
            return json_encode($context);
        } elseif (is_string($context)) {
            return $context;
        } elseif (is_object($context) && $context instanceof Arrayable) {
            return json_encode($context->toArray());
        } elseif (is_object($context) && $context instanceof Jsonable) {
            return $context->toJson();
        } else {
            return "";
        }
    }

    /**
     * @return string
     * @throws \Exception
     */

    public static function processId()
    {
        if (self::$processId === null) {
            self::$processId = Uuid::generate()->string;
        }
        return self::$processId;
    }
}