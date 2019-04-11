<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 * @date 2019-04-11
 */

use koa\web\Application;
use koa\web\Context;
use koa\web\middleware\CorsMiddleware;
use koa\web\middleware\LoggerMiddleware;

require __DIR__ . '/../vendor/autoload.php';
$app = new Application();
$app->use(new LoggerMiddleware(), new CorsMiddleware());
$app->use(function (Context $ctx) {
    $ctx->body = json_decode($ctx->request->rawContent(), true);
});
$app->run();