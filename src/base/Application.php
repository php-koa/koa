<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 * @date 2019-04-10
 */

namespace koa\base;

use koa\di\Container;

/**
 * Class Application
 * @package koa\base
 */
abstract class Application extends BaseObject
{
    const EVENT_START = 'start';
    /**
     * @var string 应用名称
     */
    public $name = 'swoole app';

    /**
     * @var string 应用版本
     */
    public $version = '1.0.0';

    /**
     * @var Container
     */
    public $container;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->container = new Container();
    }

    /**
     * 启动应用
     * @return mixed
     */
    abstract public function run();
}