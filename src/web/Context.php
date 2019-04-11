<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 * @date 2019-04-10
 */

namespace koa\web;

use koa\base\BaseObject;
use Swoole\Http\Request;
use Swoole\Http\Response;

/**
 * 请求上下文
 * Class Context
 * @package koa\web
 */
class Context extends BaseObject
{
    /**
     * @var Application
     */
    public $app;

    /**
     * @var Request 请求对象
     */
    public $request;

    /**
     * @var Response 响应对象
     */
    public $response;

    /**
     * @var array 请求状态
     */
    public $state = [];

    /**
     * @var int 响应状态码
     */
    public $statusCode = 200;

    /**
     * @var mixed 响应体
     */
    public $body = '';
}