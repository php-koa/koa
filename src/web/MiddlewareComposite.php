<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 * @date 2019-04-10
 */

namespace koa\web;

/**
 * 中间件柯里化
 * Trait MiddlewareComposite
 * @package koa\base
 */
trait MiddlewareComposite
{
    /**
     * 中间件函数柯里化
     * @param callable[] $middlewares
     * @param callable $next
     * @return callable
     */
    protected function composite(array $middlewares, callable $next)
    {
        // 翻转中间件
        $middlewares = array_reverse($middlewares);
        // 串联中间件
        foreach ($middlewares as $middleware) {
            $next = function (Context $ctx) use ($middleware, $next) {
                return $middleware($ctx, $next);
            };
        }
        return $next;
    }
}