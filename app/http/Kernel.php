<?php namespace app\http;

use fly\contracts\http\Kernel as BaseKernel;

class Kernel extends BaseKernel
{

    public function __construct()
    {
//        date_default_timezone_set('PRC');
    }

    /**
     * 框架全局中间件
     * @var array
     */
    protected $middleware = [
        \fly\http\middleware\Session::class,
    ];

    /**
     * 框架启动需要的服务
     * @var array
     */
    protected $provider = [
        \fly\provider\Config::class,
        \fly\provider\Log::class,
        \fly\provider\DB::class,
        \fly\provider\Redis::class,
        \fly\provider\Cache::class,
        \fly\provider\Pipeline::class,
        \fly\provider\http\View::class,
        \fly\provider\http\Error::class,
        \fly\provider\http\Request::class,
        \fly\provider\http\Response::class,
        \fly\provider\http\Session::class,
        \app\http\provider\Router::class,
    ];
}