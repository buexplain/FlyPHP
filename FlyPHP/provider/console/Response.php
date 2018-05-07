<?php namespace fly\provider\console;

use fly\contracts\provider\Provider;

class Response extends Provider
{
    public function register()
    {
        $this->app->set(\fly\contracts\console\Response::class, \fly\console\Response::class);
    }
}