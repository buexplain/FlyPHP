<?php namespace fly\provider\http;

use fly\contracts\provider\Provider;

class Request extends Provider
{
    public function register()
    {
        $this->app->set(\fly\contracts\http\Request::class, \fly\http\Request::class);
    }
}