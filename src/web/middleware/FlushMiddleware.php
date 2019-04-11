<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 * @date 2019-04-10
 */

namespace koa\web\middleware;


use koa\base\Middleware;
use koa\web\Context;

/**
 * 响应输出中间件
 * Class FlushMiddleware
 * @package koa\web\middleware
 */
class FlushMiddleware implements Middleware
{
    /**
     * 请求处理接口
     * @return callable
     */
    public function handle(): callable
    {
        return function (Context $ctx, $next) {
            $next($ctx);
            $ctx->response->status($ctx->statusCode);
            if (is_array($ctx->body) || is_object($ctx->body)) {
                $ctx->response->header('Content-Type', 'application/json;charset=utf-8');
                $ctx->response->end(json_encode($ctx->body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            } else {
                $ctx->response->end($ctx->body);
            }
        };
    }
}