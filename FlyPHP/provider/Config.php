<?php namespace fly\provider;

use fly\contracts\provider\Provider;

class Config extends Provider
{
    public function register()
    {
        $this->app->set(\fly\contracts\config\Config::class, new \fly\config\Config($this->app->getPath('config')));
    }
}