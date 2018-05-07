<?php namespace fly\provider\http;

use fly\contracts\provider\Provider;

class Error extends Provider
{
    public function register()
    {
        $this->app->set(\fly\contracts\http\Error::class, new \fly\http\error\Error($this->app));
    }
}