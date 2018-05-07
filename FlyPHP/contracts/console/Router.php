<?php namespace fly\contracts\console;

/**
 * 控制台路由接口
 * @package fly\contracts\console
 */
interface Router
{
    /**
     * 添加一条命令到当前路由容器
     * @param $command
     * @param $controller
     * @return \fly\router\StaticRoute
     */
    public function add($command, $controller);

    /**
     * 返回所有命令
     * @return array
     */
    public function getCommands();
}