<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 * @date 2019-04-11
 */

namespace koa\web\middleware;


use koa\base\Middleware;
use koa\web\Context;

/**
 * CORS跨域中间件
 * Class CorsMiddleware
 * @package koa\web\middleware
 */
class CorsMiddleware implements Middleware
{
    /**
     * @var array CORS设置
     */
    public $cors = [
        'Origin' => ['*'],
        'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
        'Access-Control-Request-Headers' => ['Content-Type'],
        'Access-Control-Allow-Credentials' => null,
        'Access-Control-Max-Age' => 86400,
        'Access-Control-Expose-Headers' => [],
    ];

    /**
     * 请求处理接口
     * @return callable
     */
    public function handle(): callable
    {
        return function (Context $ctx, $next) {
            $headers = [];
            if (isset($ctx->request->header['origin'], $this->cors['Origin'])) {
                if (in_array($ctx->request->header['origin'], $this->cors['Origin'], true)) {
                    $headers['Access-Control-Allow-Origin'] = $ctx->request->header['origin'];
                }
            }
            if (in_array('*', $this->cors['Origin'], true)) {
                $headers['Access-Control-Allow-Origin'] = '*';
            }

            if (isset($ctx->request->header['Access-Control-Request-Method'])) {
                $headers['Access-Control-Allow-Methods'] = implode(',', $this->cors['Access-Control-Request-Method']);
            }

            if (in_array('*', $this->cors['Access-Control-Request-Headers'], true)) {
                $headers['Access-Control-Allow-Headers'] = '*';
            } elseif (isset($this->cors['Access-Control-Request-Headers'])) {
                $headers['Access-Control-Allow-Headers'] = implode(',', $this->cors['Access-Control-Request-Headers']);
            }

            if (isset($this->cors['Access-Control-Allow-Credentials'])) {
                $headers['Access-Control-Allow-Credentials'] = $this->cors['Access-Control-Allow-Credentials'] ? 'true' : 'false';
            }

            if (isset($this->cors['Access-Control-Max-Age']) && $ctx->request->server['request_method'] == 'OPTIONS') {
                $headers['Access-Control-Max-Age'] = $this->cors['Access-Control-Max-Age'];
            }

            if (isset($this->cors['Access-Control-Expose-Headers'])) {
                $headers['Access-Control-Expose-Headers'] = implode(', ', $this->cors['Access-Control-Expose-Headers']);
            }
            foreach ($headers as $k => $v) {
                $ctx->response->header($k, $v);
            }
            $next($ctx);
        };
    }
}