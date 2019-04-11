<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 * @date 2019-04-09
 */

namespace koa\caching;

/**
 * 缓存
 * Class Cache
 * @package koa\caching
 */
abstract class Cache implements CacheInterface
{
    /**
     * @param string $key
     * @param callable $callable
     * @param int $duration
     * @return bool|mixed
     */
    public function getOrSet($key, callable $callable, $duration = 0)
    {
        $data = $this->get($key);
        if (false !== $data) {
            return $data;
        }
        $data = call_user_func($callable);
        $this->set($key, $data, $duration);
        return $data;
    }
}