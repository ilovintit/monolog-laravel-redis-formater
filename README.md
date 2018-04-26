# monolog-laravel-redis-formater
laravel日志写入Redis格式器

在`bootstrap/app.php`文件里面加入以下代码

```php
$app->configureMonologUsing(function ($monolog) {
    $monolog->setHandlers([(new \Monolog\Handler\RedisHandler(new \Predis\Client([
        'scheme' => 'tcp',
        'host' => env('REDIS_MONOLOG_HOST', env('REDIS_HOST', '127.0.0.1')),
        'port' => env('REDIS_MONOLOG_PORT', env('REDIS_PORT', 6379)),
        'password' => env('REDIS_MONOLOG_PASSWORD', env('REDIS_PASSWORD', null))
    ]), env('REDIS_MONOLOG_NAME', 'RedisMonolog')))->setFormatter(new \Iit\RedisMonolog\RedisFormatter())]);
});
```
