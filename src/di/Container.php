<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 * @date 2019-04-11
 */

namespace koa\di;

use koa\base\BaseObject;
use koa\base\InvalidConfigException;

/**
 * 容器类
 * Class Container
 * @package koa\di
 */
class Container extends BaseObject
{
    /**
     * @var array
     */
    private $_singletons = [];
    /**
     * @var array 实例对象
     */
    private $_definitions = [];
    /**
     * @var array
     */
    private $_params = [];

    /**
     * 设置实例类
     * @param string $class
     * @param array $params
     * @throws InvalidConfigException
     */
    public function set($class, $params = [])
    {
        if (empty($params['class'])) {
            throw new InvalidConfigException("The 'class' options is required");
        }
        $this->_definitions[$class] = null;
        unset($this->_singletons[$class]);
        $this->_params[$class] = $params;
    }

    /**
     * 设置单例类
     * @param string $class
     * @param array $params
     * @throws InvalidConfigException
     */
    public function setSingleton($class, $params = [])
    {
        if (empty($params['class'])) {
            throw new InvalidConfigException("The 'class' options is required");
        }
        $this->_singletons[$class] = null;
        unset($this->_definitions[$class]);
        $this->_params[$class] = $params;
    }

    /**
     * 获取实例
     * @param string $class
     * @param array $params
     * @return mixed
     * @throws ClassNotFoundException
     */
    public function get($class, $params = [])
    {
        if (!empty($this->_singletons[$class])) {
            return $this->_singletons[$class];
        }

        $params = array_merge($this->_params[$class], $params);
        $className = $params['class'];
        unset($params['class']);
        if (array_key_exists($class, $this->_singletons)) {
            $this->_singletons[$class] = new $className($params);
            return $this->_singletons[$class];
        }

        if (array_key_exists($class, $this->_definitions)) {
            return new $className($params);
        }

        throw new ClassNotFoundException("Class $class not found");
    }
}