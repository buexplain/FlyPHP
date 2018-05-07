<?php namespace fly\console;

use fly\container\Container;
use fly\contracts\console\App as InterfaceApp;
use fly\contracts\console\Kernel;
use fly\contracts\provider\Provider;
use fly\contracts\console\Request;
use fly\contracts\console\Router;
use fly\contracts\console\Response;
use Exception;
use Throwable;

/**
 * 控制台app类
 * @package fly\console
 */
class App extends Container implements InterfaceApp
{
    /**
     * 实例本身
     * @var
     */
    protected static $instance;

    /**
     * 当前app的驱动
     * @var Kernel
     */
    protected $kernel;

    /**
     * 各种目录地址
     * @var array
     */
    protected $path = [];

    public function __construct(Kernel $kernel, $basePath)
    {
        static::$instance = $this;
        $this->kernel     = $kernel;
        $this->setPath($basePath);

        $this->set(\fly\contracts\console\App::class, $this);
        $this->set(\fly\console\App::class, $this);

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
     * console框架启动入口
     */
    public function run()
    {
        try {
            if(PHP_SAPI != 'cli') {
                throw new Exception('Please run in the CLI mode');
            }

            $request = $this->get(Request::class);
            $commandName = $request->get('commandName', 'help');
            $router = $this->get(Router::class);

            $route = $router->match($commandName, $router::METHOD);
            if(is_null($route)) {
                throw new Exception('Command "'.$commandName.'" is not defined.');
            }

            $commandClass = $route->getController();
            $commandController = $this->get($commandClass);
            if($request->get('-h')) {
                return $this->get(Response::class)->info($commandController->help());
            }

            $action = $router::ACTION;
            return $commandController->$action();
        } catch (Exception $e) {
            return $this->get(Response::class)->error($e->getMessage());
        } catch (Throwable $e) {
            return $this->get(Response::class)->error($e->getMessage());
        }
    }
}