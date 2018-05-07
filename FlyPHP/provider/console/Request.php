<?php namespace fly\provider\console;

use fly\contracts\provider\Provider;

class Request extends Provider
{
    public function register()
    {
        $this->app->set(\fly\contracts\console\Request::class, \fly\console\Request::class);
    }
}