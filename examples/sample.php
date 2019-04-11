<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 * @date 2019-04-10
 */

use koa\web\Application;
use koa\web\Context;
use koa\web\middleware\LoggerMiddleware;
use koa\web\middleware\RouterMiddleware;
use Swoole\Http\Server;

require __DIR__ . '/../vendor/autoload.php';
$app = new Application(['name' => 'demo', 'version' => '2.0']);
$app->use(new LoggerMiddleware());

$router = new RouterMiddleware(['prefix' => '/user']);
$router->get('/login', function (Context $ctx, $next) {
    $ctx->state['name'] = $ctx->request->get['name'];
    $next($ctx);
});
$router->get('/login', function (Context $ctx) {
    $ctx->body = $ctx->state;
});
$app->use($router);

$app->on(Application::EVENT_START, function (Server $server) {
    printf("listen on %s:%d\n", $server->host, $server->port);
});
$app->run();