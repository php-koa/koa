<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 * @date 2019-04-12
 */

use koa\caching\CacheInterface;
use koa\caching\RedisCache;
use koa\web\Application;
use koa\web\Context;
use koa\web\middleware\CacheMiddleware;
use koa\web\middleware\LoggerMiddleware;

require __DIR__ . '/../vendor/autoload.php';
$app = new Application();
$app->container->set(CacheInterface::class, [
    'class' => RedisCache::class,
]);
$app->use(new LoggerMiddleware(), new CacheMiddleware([
    'cache' => CacheInterface::class,
    'pattern' => '/^\/user/'
]));
// 清除缓存
//$app->use(function (Context $ctx) {
//    $ctx->state['cache.flush']('/user/login');
//    $ctx->body = '123';
//});
$app->use(function (Context $ctx) {
    $ctx->body = ['name' => $ctx->app->name, 'version' => $ctx->app->version];
});
$app->run();