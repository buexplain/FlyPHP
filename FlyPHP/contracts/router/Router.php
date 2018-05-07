<?php namespace fly\contracts\router;


interface Router
{
    public function match($uri, $method);
    public function get($uri, $controller, $action);
    public function head($uri, $controller, $action);
    public function post($uri, $controller, $action);
    public function delete($uri, $controller, $action);
    public function put($uri, $controller, $action);
    public function patch($uri, $controller, $action);
    public function any($uri, $controller, $action);
    public function controller($uri, $controller);
}