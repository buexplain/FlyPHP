<?php namespace app\http;

use fly\http\Router as BaseRouter;

class Router extends BaseRouter
{
    public function __construct()
    {
        $this->get('/', \app\http\controller\Example::class, 'index')->middleware(\app\http\middleware\Auth::class);
        $this->any('/ajax', \app\http\controller\Example::class, 'ajax');
        $this->any('/up', \app\http\controller\Example::class, 'upload');
    }
}