<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 * @date 2019-04-12
 */

namespace koa\web\middleware;


use koa\base\BaseObject;
use koa\base\InvalidConfigException;
use koa\base\Middleware;
use koa\caching\CacheInterface;
use koa\web\Context;

/**
 * 缓存中间件，根据URL对响应结果进行缓存
 * Class CacheMiddleware
 * @package koa\web\middleware
 */
class CacheMiddleware extends BaseObject implements Middleware
{
    /**
     * @var CacheInterface|string
     */
    public $cache;
    /**
     * @var array 缓存方法
     */
    public $methods = ['GET'];
    /**
     * @var string 缓存前缀
     */
    public $keyPrefix = 'req:';

    /**
     * @var string URL正则
     */
    public $pattern = '/.*/';

    /**
     * @var int 缓存时间
     */
    public $duration = 7200;

    public function init()
    {
        if (empty($this->cache)) {
            throw new InvalidConfigException("The 'cache' property must be set");
        }
    }

    /**
     * 请求处理接口
     * @return callable
     */
    public function handle(): callable
    {
        return function (Context $ctx, $next) {
            // 注册缓存清除方法
            $ctx->state['cache.flush'] = [$this, 'clean'];
            // 注入Cache
            if (is_string($this->cache)) {
                $this->cache = $ctx->app->container->get($this->cache);
            }

            // 请求方法无需处理，直接放行
            if (!in_array($ctx->request->server['request_method'], $this->methods)) {
                $next($ctx);
                return;
            }
            if (!preg_match($this->pattern, $ctx->request->server['path_info'])) {
                $next($ctx);
                return;
            }
            $cacheKey = $this->buildCacheKey($ctx);
            // 读取缓存,如果缓存不存在,则执行后续中间件得到响应数据并返回
            $ctx->body = $this->cache->getOrSet($cacheKey, function () use ($ctx, $next) {
                $next($ctx);
                return serialize($ctx->body);
            }, $this->duration);
            $ctx->body = unserialize($ctx->body);
        };
    }

    /**
     * 构建缓存key
     * @param Context $ctx
     * @return string
     */
    private function buildCacheKey(Context $ctx)
    {
        $str = $this->keyPrefix . $ctx->request->server['path_info'];
        if (!empty($ctx->request->server['query_string'])) {
            $str .= '?' . $ctx->request->server['query_string'];
        }
        return $str;
    }

    /**
     * 删除缓存
     * @param $uri
     */
    public function clean($uri)
    {
        $this->cache->delete($this->keyPrefix . $uri);
    }
}