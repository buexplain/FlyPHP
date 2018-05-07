<?php namespace fly\provider\http;

use fly\contracts\provider\Provider;

class Router extends Provider
{
    public function register()
    {
        $this->app->set(\fly\contracts\router\Router::class, \fly\http\Router::class);
    }
}