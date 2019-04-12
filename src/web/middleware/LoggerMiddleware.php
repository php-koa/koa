<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 * @date 2019-04-10
 */

namespace koa\web\middleware;

use koa\base\BaseObject;
use koa\base\Middleware;
use koa\web\Context;

/**
 * 日志中间件
 * Class LoggerMiddleware
 * @package koa\web\middleware
 */
class LoggerMiddleware extends BaseObject implements Middleware
{
    /**
     * 请求处理接口
     * @return callable
     */
    public function handle(): callable
    {
        return function (Context $ctx, $next) {
            $start = microtime(true);
            $next($ctx);
            $url = $ctx->request->server['path_info'];
            if (!empty($ctx->request->server['query_string'])) {
                $url .= '?' . $ctx->request->server['query_string'];
            }
            printf(
                "[%s] %s [%d]: %s %.6fs\n",
                date('Y-m-d H:i:s'),
                $ctx->request->header['x-forwarded-for'] ?? $ctx->request->server['remote_addr'],
                $ctx->statusCode,
                $url,
                microtime(true) - $start
            );
        };
    }
}