<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 * @date 2019-04-10
 */

namespace koa\base;

/**
 * 异常类
 * Class Exception
 * @package koa\base
 */
class Exception extends \Exception
{
    public function getName()
    {
        return 'Exception';
    }
}