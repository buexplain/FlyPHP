<?php namespace fly\provider\console;

use fly\contracts\provider\Provider;

class Router extends Provider
{
    public function register()
    {
        $this->app->set(\fly\contracts\console\Router::class, \fly\console\Router::class);
    }
}