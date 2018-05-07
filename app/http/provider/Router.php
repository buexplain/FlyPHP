<?php namespace app\http\provider;

use fly\provider\http\Router as BaseRouter;

class Router extends BaseRouter
{
    public function register()
    {
        $this->app->set(\fly\contracts\router\Router::class, \app\http\Router::class);
    }
}