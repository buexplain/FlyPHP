<?php

use fly\contracts\http\Facade;
use fly\http\App;

/**
 * @method static string getId()
 * @method static array all()
 * @method static mixed|null get($key, $default=null)
 * @method static mixed|null pull($key, $default=null)
 * @method static  set($key, $value)
 * @method static  del($key)
 * @method static regenerate()
 * @method static clear()
 * @method static save()
 */
class Session extends Facade
{
    protected static function getFacadeClass()
    {
        return App::getInstance()->get(\fly\contracts\http\Session::class);
    }
}