<?php namespace fly\provider;

use fly\contracts\provider\Provider;

class Pipeline extends Provider
{
    public function register()
    {
        $this->app->set(\fly\contracts\pipeline\Pipeline::class, \fly\pipeline\Pipeline::class, false);
    }
}