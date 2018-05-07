<?php namespace fly\provider\http;

use fly\contracts\provider\Provider;

class Response extends Provider
{
    public function register()
    {
        $this->app->set(\fly\contracts\http\Response::class, \fly\http\Response::class);
    }
}