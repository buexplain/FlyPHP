<?php namespace app\console\provider;

use fly\provider\console\Router as BaseRouter;

class Router extends BaseRouter
{
    public function register()
    {
        $this->app->set(\fly\contracts\console\Router::class, \app\console\Router::class);
    }
}