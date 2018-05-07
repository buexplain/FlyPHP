<?php namespace fly\http;

use fly\contracts\http\App as InterfaceApp;
use fly\contracts\http\Kernel;
use fly\contracts\pipeline\Pipeline;
use fly\contracts\router\Router;
use fly\contracts\http\Middleware;
use fly\contracts\provider\Provider;
use fly\contracts\http\Request;
use fly\contracts\http\Response;
use fly\container\Container;
use fly\http\error\AbortException;

/**
 * web框架app类
 * @package fly\http
 */
class App extends Container implements InterfaceApp
{
    /**
     * 实例本身
     * @var
     */
    protected static $instance;

    /**
     * 驱动当前app的内核
     * @var Kernel
     */
    protected $kernel;

    /**
     * 各种目录地址
     * @var array
     */
    protected $path = [];

    /**
     * 当前请求命中的路由
     * @var
     */
    protected $route = null;

    /**
     * 构造函数
     * App constructor.
     * @param Kernel $kernel
     * @param string $basePath
     */
    public function __construct(Kernel $kernel, $basePath)
    {
        static::$instance = $this;
        $this->kernel     = $kernel;
        $this->setPath($basePath);

        $this->set(\fly\contracts\http\App::class, $this);
        $this->set(\fly\http\App::class, $this);

        $this->set(\fly\contracts\container\Container::class, $this);
        $this->set(\fly\container\Container::class, $this);

        array_map(function($provider) {
            $tmp = $this->set($provider)->get($provider);
            if($tmp instanceof Provider) {
                $tmp->register();
            }
        }, $this->kernel->getProvider());
    }

    /**
     * 返回$this
     * @return $this
     */
    public static function getInstance()
    {
        return static::$instance;
    }

    /**
     * 设置各种目录
     * @param string $basePath
     */
    protected function setPath($basePath)
    {
        $this->path = [
            'base'=>$basePath,
            'vendor'=>$basePath.DIRECTORY_SEPARATOR.'vendor',
            'config'=>$basePath.DIRECTORY_SEPARATOR.'config',
            'cache'=>$basePath.DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'cache',
            'log'=>$basePath.DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'log',
            'session'=>$basePath.DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'session',
            'view'=>$basePath.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'http'.DIRECTORY_SEPARATOR.'view',
            'viewCache'=>$basePath.DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'view',
            'runtime'=>$basePath.DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'runtime',
        ];
    }

    /**
     * 返回各种目录地址
     * @param string $pathName
     * @return string
     */
    public function getPath($pathName)
    {
        return $this->path[$pathName];
    }

    /**
     * 返回当前请求的路由
     * @return \fly\contracts\router\Route|null
     */
    public function getCurrentRoute()
    {
        return $this->route;
    }

    /**
     * 中断请求
     * @param string $message
     * @param int $httpCode
     * @param null|Response::FORMAT_HTML|Response::FORMAT_JSON|Response::FORMAT_JSONP $format
     * @see \fly\contracts\http\Response
     */
    public function abort($message, $httpCode, $format=null)
    {
        if(is_null($format)) {
            if($this->get(Request::class)->is_ajax()) {
                $format = Response::FORMAT_JSON;
            }else{
                $format = Response::FORMAT_HTML;
            }
        }
        throw new AbortException($message, $httpCode, $format);
    }

    /**
     * web框架启动入口
     */
    public function run()
    {
        $request  = $this->get(Request::class);
        $kernelMiddleware = $this->kernel->getMiddleware();

        $response = $this->get(Pipeline::class, ['handle'=>Middleware::BEFORE])->send($request)->through($kernelMiddleware)->then(function($request) use(&$kernelMiddleware) {

            $route = $this->get(Router::class)->match($request->path(), $request->method());

            if(is_null($route)) {
                $this->abort('404 Not Found Route', 404);
            }

            $this->route = $route;

            $routeMiddleware = $route->getMiddleware();

            return $this->get(Pipeline::class, ['handle'=>Middleware::BEFORE])->send($request)->through($routeMiddleware)->then(function($request) use(&$route, &$kernelMiddleware, &$routeMiddleware) {

                $controllerName = $route->getController();
                $actionName     = $route->getAction();

                $controller  = $this->get($controllerName);
                $actionParam = $this->getMethodParam($controllerName, $actionName, $route->getParams());

                $response = call_user_func_array([$controller, $actionName], $actionParam);

                unset($controller);

                if(!($response instanceof Response)) {
                    $this->abort('The controller must return an instance of the "'.Response::class.'" interface', 500);
                }

                return $this->get(Pipeline::class, ['handle'=>Middleware::AFTER])->send($response)->through($routeMiddleware)->then(function($response) use(&$request, &$kernelMiddleware) {
                    return $this->get(Pipeline::class, ['handle'=>Middleware::AFTER])->send($response)->through($kernelMiddleware)->then(function($response) {
                        return $response;
                    });
                });

            });

        });

        if(!($response instanceof Response)) {
            $this->abort('The middleware must return an instance of the "'.Response::class.'" interface', 500);
        }

        $response->send();
    }
}