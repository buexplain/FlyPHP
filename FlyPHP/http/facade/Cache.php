<?php

use fly\contracts\http\Facade;
use fly\http\App;

/**
 * @method static bool set($key, $value, $sec=3600)
 * @method static array|integer|string get($key, $default=null)
 * @method static bool has($key)
 * @method static int del($key)
 * @method static \fly\contracts\cache\Cache change($key)
 */
class Cache extends Facade
{
    protected static function getFacadeClass()
    {
        return App::getInstance()->get(\fly\contracts\cache\CacheManager::class);
    }
}