<?php

use fly\contracts\http\Facade;
use fly\http\App;

/**
 * @method static string method();
 * @method static string path();
 * @method static bool is_ajax();
 * @method static mixed get($key, $default=null);
 * @method static mixed|null cookie($key, $default=null);
 * @method static array|\fly\contracts\http\UploadFile|null file($key);
 */
class Request extends Facade
{
    protected static function getFacadeClass()
    {
        return App::getInstance()->get(\fly\contracts\http\Request::class);
    }
}