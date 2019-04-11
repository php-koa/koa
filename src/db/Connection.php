<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 * @date 2019-04-10
 */

namespace koa\db;

use koa\base\BaseObject;
use Swoole\Coroutine\MySQL;
use Swoole\Coroutine\Mysql\Statement;

/**
 * 数据库连接
 * Class Connection
 * @package koa\db
 */
class Connection extends BaseObject
{
    /**
     * @var MySQL
     */
    private $_connection;

    /**
     * @var array 连接配置
     */
    public $config;

    /**
     * 打开数据库连接
     * @throws Exception
     */
    public function open()
    {
        if (isset($this->_connection) && $this->_connection->connected) {
            return;
        }
        $this->_connection = new MySQL();
        if (!$this->_connection->connect($this->config)) {
            throw new Exception($this->_connection->error, [], $this->_connection->errno);
        }
    }

    /**
     * 关闭数据库连接
     */
    public function close()
    {
        if (isset($this->_connection)) {
            $this->_connection->close();
            $this->_connection = null;
        }
    }

    /**
     * SQL查询
     * @param string $sql
     * @param float $timeout
     * @return array|bool
     * @throws Exception
     */
    public function query($sql, $timeout = 0.0)
    {
        $this->open();
        return $this->_connection->query($sql, $timeout);
    }

    /**
     * SQL预处理
     * @param $sql
     * @return Statement
     * @throws Exception
     */
    public function prepare($sql)
    {
        $this->open();
        return $this->_connection->prepare($sql);
    }

    /**
     * 开始事务
     * @throws Exception
     */
    public function begin()
    {
        $this->open();
        $this->_connection->begin();
    }

    /**
     * 提交事务
     * @throws Exception
     */
    public function commit()
    {
        $this->open();
        $this->_connection->commit();
    }

    /**
     * 回滚事务
     * @throws Exception
     */
    public function rollback()
    {
        $this->open();
        $this->_connection->rollback();
    }

    /**
     * 闭包执行事务，抛出异常时自动回滚
     * @param callable $callable
     * @return mixed
     * @throws Exception
     */
    public function transaction(callable $callable)
    {
        try {
            $this->begin();
            $result = call_user_func($callable);
            $this->commit();
            return $result;
        } catch (\Exception $e) {
            $this->rollback();
            throw $e;
        }
    }

    public function __destruct()
    {
        $this->close();
    }
}