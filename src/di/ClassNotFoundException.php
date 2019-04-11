<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 * @date 2019-04-11
 */

namespace koa\di;


use koa\base\Exception;

/**
 * 类不存在
 * Class ClassNotFoundException
 * @package koa\di
 */
class ClassNotFoundException extends Exception
{
    public function getName()
    {
        return 'ClassNotFoundException';
    }
}