<?php namespace fly\provider;

use fly\contracts\provider\Provider;

class Cache extends Provider
{
    public function register()
    {
        $this->app->set(\fly\contracts\cache\CacheManager::class, function($app) {
            return new \fly\cache\CacheManager($app);
        });
    }
}