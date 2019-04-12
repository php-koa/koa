<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 * @date 2019-04-12
 */

namespace koa\base;

/**
 * 对象配置异常
 * Class InvalidConfigException
 * @package koa\base
 */
class InvalidConfigException extends Exception
{
    public function getName()
    {
        return 'Invalid Configuration';
    }
}