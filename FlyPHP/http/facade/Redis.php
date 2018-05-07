<?php

use fly\contracts\http\Facade;
use fly\http\App;

/**
 * @method static bool set($key, $value, $sec=3600)
 * @method static array|integer|string get($key, $default=null)
 * @method static bool has($key)
 * @method static int del($key1, $key2=null, $key3=null)
 * @method static int inc($key, $step=1)
 * @method static int dec($key, $step=1)
 * @method static \fly\contracts\redis\Redis change($key)
 */
class Redis extends Facade
{
    protected static function getFacadeClass()
    {
        return App::getInstance()->get(\fly\contracts\redis\RedisManager::class);
    }
}