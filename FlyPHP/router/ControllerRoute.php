<?php namespace fly\router;

use fly\contracts\router\Route;

/**
 * 控制器类型的路由
 * Class ControllerRoute
 */
class ControllerRoute extends Route
{

    public function __construct($uri, $controller)
    {
        $this->uri = trim($uri, '/');
        $this->controller = $controller;
    }

    public function match(array $array, $method)
    {
        $this->action = '';

        if(count($array) > 1) {
            $action = array_pop($array);
        }else{
            $action = 'index';
        }

        if(implode('/', $array) == $this->uri) {
            $this->action = strtolower($method).ucfirst($action);
            return true;
        }else{
            return false;
        }
    }
}