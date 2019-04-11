<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 * @date 2019-04-10
 */

namespace koa\web;

use koa\base\Exception;
use koa\base\Middleware;
use koa\web\middleware\FlushMiddleware;
use koa\web\middleware\NotFoundMiddleware;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

/**
 * Web应用
 * Class Application
 * @package koa\web
 */
class Application extends \koa\base\Application
{
    use MiddlewareComposite;
    const EVENT_REQUEST = 'request';
    /**
     * @var string 监听主机
     */
    public $host = 'localhost';

    /**
     * @var int 监听端口
     */
    public $port = 9501;

    /**
     * @var int 运行模式
     */
    public $mode = SWOOLE_PROCESS;

    /**
     * @var int socket类型
     */
    public $sock_type = SWOOLE_SOCK_TCP;

    /**
     * @var callable[] 中间件列表
     */
    protected $_middlewares = [];
    /**
     * @var Server swoole 服务器
     */
    protected $_server = null;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->_server = new Server($this->host, $this->port, $this->mode, $this->sock_type);
        $this->use(new FlushMiddleware());
    }

    /**
     * 使用中间件
     * @param Middleware|callable ...$middlewares
     * @return Application
     * @throws Exception
     */
    public function use(...$middlewares)
    {
        foreach ($middlewares as $middleware) {
            if (is_callable($middleware)) {
                $this->_middlewares[] = $middleware;
                continue;
            }
            if ($middleware instanceof Middleware) {
                $this->_middlewares[] = $middleware->handle();
                continue;
            }
            throw new Exception('Middleware must be callable or implement Middleware interface');
        }
        return $this;
    }

    /**
     * 设置服务器选项
     * @param array $setting
     */
    public function set(array $setting)
    {
        $this->_server->set($setting);
    }

    /**
     * 监听服务器事件
     * @param string $event
     * @param callable $func
     */
    public function on($event, callable $func)
    {
        $this->_server->on($event, $func);
    }

    /**
     * 启动应用
     * @return mixed
     */
    public function run()
    {
        $this->_server->on(self::EVENT_REQUEST, [$this, 'onRequest']);
        return $this->_server->start();
    }

    /**
     * 处理请求
     * @param Request $request
     * @param Response $response
     */
    public function onRequest(Request $request, Response $response)
    {
        $ctx = new Context([
            'request' => $request,
            'response' => $response,
            'app' => $this
        ]);
        $notFound = new NotFoundMiddleware();
        $next = $notFound->handle();
        $next = $this->composite($this->_middlewares, $next);
        $next($ctx);
    }
}