<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 * @date 2019-04-10
 */

namespace koa\web\middleware;


use koa\base\Middleware;
use koa\web\Context;

/**
 * Class NotFoundMiddleware
 * @package koa\web\middleware
 */
class NotFoundMiddleware implements Middleware
{

    /**
     * 请求处理接口
     * @return callable
     */
    public function handle(): callable
    {
        return function (Context $ctx) {
            $ctx->statusCode = 404;
            $ctx->body = '404 Not Found';
        };
    }
}