<?php namespace fly\provider;

use fly\contracts\provider\Provider;

class Log extends Provider
{
    public function register()
    {
        $this->app->set(\fly\contracts\log\Log::class, new \fly\log\File($this->app->get(\fly\contracts\config\Config::class)->get('log'), $this->app->getPath('log')));
    }
}