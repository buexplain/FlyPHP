<?php namespace fly\console;

use fly\router\Router as BaseRouter;
use fly\contracts\console\Router as InterfaceRouter;

/**
 * 控制台路由
 * @package fly\console
 */
class Router extends BaseRouter implements InterfaceRouter
{
    /**
     * 默认的action入口名称
     */
    const ACTION = 'run';

    /**
     * 默认的请求类型
     */
    const METHOD = 'GET';

    /**
     * 当前命令容器
     * @var array
     */
    protected $commands = [];

    /**
     * 添加一条命令到当前路由容器
     * @param $command
     * @param $controller
     * @return \fly\router\StaticRoute
     */
    public function add($command, $controller)
    {
        array_unshift($this->commands, $command);
        return parent::addStaticRoute($command, $controller, self::ACTION, self::METHOD);
    }

    /**
     * 返回所有命令
     * @return array
     */
    public function getCommands()
    {
        return $this->commands;
    }
}