<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 * @date 2019-04-10
 */

namespace koa\base;

/**
 * 中间件
 * Interface Middleware
 * @package koa\base
 */
interface Middleware
{
    /**
     * 请求处理接口
     * @return callable
     */
    public function handle(): callable;
}