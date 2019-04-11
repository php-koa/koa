<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 * @date 2019-04-11
 */

use koa\db\Connection;
use koa\web\Application;
use koa\web\Context;
use koa\web\middleware\LoggerMiddleware;

require __DIR__ . '/../vendor/autoload.php';

$app = new Application();
$app->container->set(Connection::class, [
    'config' => [
        'host' => 'localhost',
        'port' => 3306,
        'user' => 'root',
        'password' => 'root',
        'database' => 'blog',
        'charset' => 'utf8mb4',
        'strict_type' => true
    ]
]);
$app->use(new LoggerMiddleware());
$app->use(function (Context $ctx) {
    /** @var Connection $conn */
    $conn = $ctx->app->container->get(Connection::class);
    $data = $conn->query('SELECT * FROM blog_category');
    $ctx->body = $data;
});
$app->run();