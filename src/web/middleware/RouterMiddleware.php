<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 * @date 2019-04-10
 */

namespace koa\web\middleware;


use koa\base\BaseObject;
use koa\base\Middleware;
use koa\web\Context;
use koa\web\MiddlewareComposite;

/**
 * 路由中间件
 * Class RouterMiddleware
 * @package koa\web\middleware
 */
class RouterMiddleware extends BaseObject implements Middleware
{
    use MiddlewareComposite;
    /**
     * @var string 请求前缀
     */
    public $prefix = '';

    /**
     * @var array 路由map [path => [method => callable[]]]
     */
    public $_map = [];

    /**
     * @var array 中间件 [path => callable[]]
     */
    private $_middlewares = [];

    /**
     * 使用中间件
     * @param callable|Middleware $middleware
     * @param string $path
     * @return $this
     */
    public function use($middleware, $path = '')
    {
        if ($path && $this->prefix) {
            $path = $this->prefix . $path;
        }
        $this->_middlewares[$path] = $this->_middlewares[$path] ?? [];
        $this->_middlewares[$path][] = $middleware instanceof Middleware ? $middleware->handle() : $middleware;
        return $this;
    }

    /**
     * 添加路由
     * @param string $method
     * @param string $path
     * @param callable $func
     * @return RouterMiddleware
     */
    public function request($method, $path, callable $func)
    {
        if ($path && $this->prefix) {
            $path = $this->prefix . $path;
        }
        $this->_map[$path] = $this->_map[$path] ?? [];
        $this->_map[$path][$method] = $this->_map[$path][$method] ?? [];
        $this->_map[$path][$method][] = $func;
        return $this;
    }

    /**
     * HEAD请求
     * @param string $path
     * @param callable $func
     * @return RouterMiddleware
     */
    public function head($path, callable $func)
    {
        return $this->request('HEAD', $path, $func);
    }

    /**
     * OPTIONS请求
     * @param string $path
     * @param callable $func
     * @return RouterMiddleware
     */
    public function options($path, callable $func)
    {
        return $this->request('OPTIONS', $path, $func);
    }

    /**
     * GET请求
     * @param string $path
     * @param callable $func
     * @return RouterMiddleware
     */
    public function get($path, callable $func)
    {
        return $this->request('GET', $path, $func);
    }

    /**
     * PUT请求
     * @param string $path
     * @param callable $func
     * @return RouterMiddleware
     */
    public function put($path, callable $func)
    {
        return $this->request('PUT', $path, $func);
    }

    /**
     * PATCH请求
     * @param string $path
     * @param callable $func
     * @return RouterMiddleware
     */
    public function patch($path, callable $func)
    {
        return $this->request('PATCH', $path, $func);
    }

    /**
     * POST请求
     * @param string $path
     * @param callable $func
     * @return RouterMiddleware
     */
    public function post($path, callable $func)
    {
        return $this->request('POST', $path, $func);
    }

    /**
     * 请求处理接口
     * @return callable
     */
    public function handle(): callable
    {
        return function (Context $ctx, $next) {
            $method = $ctx->request->server['request_method'];
            $pathinfo = $ctx->request->server['path_info'];
            // 调用路由中间件
            $middlewares = array_merge($this->middlewares[''] ?? [], $this->middlewares[$pathinfo] ?? []);
            // 调用路由处理函数
            if (isset($this->_map[$pathinfo][$method])) {
                $middlewares = array_merge($middlewares, $this->_map[$pathinfo][$method]);
            }
            $next = $this->composite($middlewares, $next);
            $next($ctx);
        };
    }
}