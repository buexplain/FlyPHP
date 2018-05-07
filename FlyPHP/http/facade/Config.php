<?php

use fly\contracts\http\Facade;
use fly\http\App;

/**
 * @method static array|mixed|null get($name)
 */
class Config extends Facade
{
    protected static function getFacadeClass()
    {
        return App::getInstance()->get(\fly\contracts\config\Config::class);
    }
}