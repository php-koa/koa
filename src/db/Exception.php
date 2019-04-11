<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 * @date 2019-04-10
 */

namespace koa\db;

use Throwable;

/**
 * 数据库异常
 * Class Exception
 * @package koa\db
 */
class Exception extends \koa\base\Exception
{
    public $errorInfo = [];

    public function __construct($message = "", $errorInfo = [], $code = 0, Throwable $previous = null)
    {
        $this->errorInfo = $errorInfo;
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return parent::__toString() . PHP_EOL
            . 'Additional Information:' . PHP_EOL . print_r($this->errorInfo) . PHP_EOL;
    }

    public function getName()
    {
        return 'DatabaseException';
    }
}