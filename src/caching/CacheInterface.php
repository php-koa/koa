<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 * @date 2019-04-09
 */

namespace koa\caching;

/**
 * 缓存接口
 * Interface CacheInterface
 * @package koa\caching
 */
interface CacheInterface
{
    /**
     * 读取缓存
     * @param string $key
     * @return mixed|bool
     */
    public function get($key);

    /**
     * 检查缓存key是否存在
     * @param string $key
     * @return bool
     */
    public function exists($key);

    /**
     * 批量读取
     * @param array $keys
     * @return array
     */
    public function multiGet($keys);

    /**
     * 设置缓存
     * @param string $key
     * @param mixed $value
     * @param int $duration
     * @return bool
     */
    public function set($key, $value, $duration = 0);

    /**
     * 批量设置
     * @param array $items
     * @param int $duration
     * @return mixed
     */
    public function multiSet(array $items, $duration = 0);

    /**
     * 删除缓存
     * @param string $key
     * @return bool
     */
    public function delete($key);

    /**
     * 批量删除
     * @param array $keys
     * @return int
     */
    public function multiDelete(array $keys);

    /**
     * 如果缓存存在，则读取并返回缓存值
     * 如果缓存不存在，则调用回调函数生成值缓存，并返回该值
     * @param string $key
     * @param callable $callable
     * @param int $duration
     * @return mixed
     */
    public function getOrSet($key, callable $callable, $duration = 0);
}