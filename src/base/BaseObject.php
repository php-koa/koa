<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 * @date 2019-04-10
 */

namespace koa\base;

/**
 * Class BaseObject
 * @package koa\base
 */
class BaseObject
{
    /**
     * 构造方法
     * BaseObject constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        if (!empty($config)) {
            $this->configuration($config);
        }
        $this->init();
    }

    /**
     * 配置属性
     * @param array $config
     * @return $this
     */
    public function configuration(array $config)
    {
        foreach ($config as $name => $value) {
            $this->$name = $value;
        }
        return $this;
    }

    /**
     * 初始化方法
     */
    public function init()
    {

    }

    /**
     * 读取属性
     * @param string $name
     * @return mixed
     * @throws Exception
     */
    public function __get($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter();
        }
        throw new Exception('Getting unknown property: ' . __CLASS__ . '::' . $name);
    }


    /**
     * 设置属性
     * @param string $name
     * @param mixed $value
     * @throws Exception
     */
    public function __set($name, $value)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter($value);
        }
        throw new Exception('Setting unknown property: ' . __CLASS__ . '::' . $name);
    }

    /**
     * 检测属性是否存在
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter() !== null;
        }
        return false;
    }

    /**
     * 移除属性
     * @param string $name
     * @throws Exception
     */
    public function __unset($name)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter(null);
        }
        throw new Exception('Setting unknown property: ' . __CLASS__ . '::' . $name);
    }

    /**
     * 调用不存在方法
     * @param string $name
     * @param array $arguments
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        throw new Exception('Calling unknown method: ' . __CLASS__ . "::$name()");
    }
}