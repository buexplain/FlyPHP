<?php namespace fly\provider;

use fly\contracts\provider\Provider;

class Redis extends Provider
{
    public function register()
    {
        $app = $this->app;
        $this->app->set(\fly\contracts\redis\RedisManager::class, function() use(&$app) {
            $config = $app->get(\fly\contracts\config\Config::class);
            return new \fly\redis\RedisManager($config->get('redis'));
        });
    }
}