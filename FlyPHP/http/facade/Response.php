<?php

use fly\contracts\http\Facade;
use fly\http\App;

/**
 * @method static \fly\contracts\http\Response with($key, $value)
 * @method static \fly\contracts\http\Response setStatusCode($code)
 * @method static \fly\contracts\http\Response setHeader($key, $value)
 * @method static \fly\contracts\http\Response setCookie($name, $value, $expire, $path='', $domain='', $secure=false, $httpOnly=false)
 * @method static int getFormat()
 * @method static \fly\contracts\http\Response view($view)
 * @method static \fly\contracts\http\Response json(array $data)
 * @method static \fly\contracts\http\Response jsonp(array $data, $callback='callback')
 */
class Response extends Facade
{
    protected static function getFacadeClass()
    {
        return App::getInstance()->get(\fly\contracts\http\Response::class);
    }
}