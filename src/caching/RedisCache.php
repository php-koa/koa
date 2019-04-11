<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 * @date 2019-04-09
 */

namespace koa\swoole\caching;

use koa\caching\Cache;
use Swoole\Coroutine\Redis;

/**
 * swoole Redis缓存
 * Class RedisCache
 * @package koa\swoole\caching
 */
class RedisCache extends Cache
{
    /**
     * @var array 连接选项
     */
    private $options = [];
    /**
     * @var Redis
     */
    private $redis;

    /**
     * RedisCache constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * 打开Redis连接
     */
    private function open()
    {
        if (isset($this->redis)) {
            return;
        }
        $this->redis = new Redis();
        $this->redis->connect($this->options['host'], $this->options['port'] ?? 6379, $this->options['timeout'] ?? 0.0);
        if (!empty($this->options['password'])) {
            $this->redis->auth($this->options['password']);
        }
    }

    /**
     * 关闭连接
     */
    private function close()
    {
        if (isset($this->redis)) {
            $this->redis->close();
            $this->redis = null;
        }
    }


    /**
     * 读取缓存
     * @param string $key
     * @return mixed|bool
     */
    public function get($key)
    {
        $this->open();
        return $this->redis->get($key);
    }

    /**
     * 检查缓存key是否存在
     * @param string $key
     * @return bool
     */
    public function exists($key)
    {
        $this->open();
        return $this->redis->exists($key) === 1;
    }

    /**
     * 批量读取
     * @param array $keys
     * @return array
     */
    public function multiGet($keys)
    {
        $this->open();
        if (method_exists($this->redis, 'getMultiple')) {
            $values = $this->redis->getMultiple($keys);
            return array_combine($keys, $values);
        }
        $values = array_map(function ($key) {
            return $this->redis->get($key);
        }, $keys);
        return array_combine($keys, $values);
    }

    /**
     * 设置缓存
     * @param string $key
     * @param mixed $value
     * @param int $duration
     * @return mixed
     */
    public function set($key, $value, $duration = 0)
    {
        $this->open();
        if ($duration === 0) {
            return $this->redis->set($key, $value);
        }
        return $this->redis->set($key, $value, $duration);
    }

    /**
     * 批量设置
     * @param array $items
     * @param int $duration
     * @return mixed
     */
    public function multiSet(array $items, $duration = 0)
    {
        $this->open();
        $result = $this->redis->mset($items);
        if ($duration > 0) {
            foreach ($items as $key => $value) {
                $result = $this->redis->expire($key, $duration);
            }
        }
        return $result;
    }

    /**
     * 删除缓存
     * @param string $key
     * @return mixed
     */
    public function delete($key)
    {
        $this->open();
        return $this->redis->delete($key) === 1;
    }

    /**
     * 批量删除
     * @param array $keys
     * @return int
     */
    public function multiDelete(array $keys)
    {
        $this->open();
        return $this->redis->delete($keys);
    }

    /**
     * 析构方法
     */
    public function __destruct()
    {
        $this->close();
    }
}