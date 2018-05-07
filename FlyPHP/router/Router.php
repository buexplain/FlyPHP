<?php namespace fly\router;

use fly\contracts\router\Router as InterfaceRouter;

/**
 * 路由容器
 * Class Router
 */
class Router implements InterfaceRouter
{
    protected $routes = array();

    public function match($uri, $method)
    {
        $uri = trim($uri, '/');
        $uri = $uri == '' ? [] : explode('/', $uri);
        $method = strtoupper($method);
        foreach($this->routes as $route) {
            if($route->match($uri, $method)) {
                return $route;
            }
        }
        return null;
    }

    protected function addStaticRoute($uri, $controller, $action, $method)
    {
        $route = new StaticRoute($uri, $controller, $action, $method);
        $this->routes[] = $route;
        return $route;
    }

    public function get($uri, $controller, $action)
    {
        return  $this->addStaticRoute($uri, $controller, $action, 'GET');
    }

    public function head($uri, $controller, $action)
    {
        return  $this->addStaticRoute($uri, $controller, $action, 'HEAD');
    }

    public function post($uri, $controller, $action)
    {
        return  $this->addStaticRoute($uri, $controller, $action, 'POST');
    }

    public function delete($uri, $controller, $action)
    {
        return  $this->addStaticRoute($uri, $controller, $action, 'DELETE');
    }

    public function put($uri, $controller, $action)
    {
        return  $this->addStaticRoute($uri, $controller, $action, 'PUT');
    }

    public function patch($uri, $controller, $action)
    {
        return  $this->addStaticRoute($uri, $controller, $action, 'PATCH');
    }

    public function any($uri, $controller, $action)
    {
        return  $this->addStaticRoute($uri, $controller, $action, 'ANY');
    }

    public function controller($uri, $controller)
    {
        $route = new ControllerRoute($uri, $controller);
        $this->routes[] = $route;
        return $route;
    }
}
