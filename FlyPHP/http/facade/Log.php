<?php

use fly\contracts\http\Facade;
use fly\http\App;

/**
 * @method static error($message, $tag=null);
 * @method static warning($message, $tag=null);
 * @method static info($message, $tag=null);
 * @method static debug($message, $tag=null);
 */
class Log extends Facade
{
    protected static function getFacadeClass()
    {
        return App::getInstance()->get(\fly\contracts\log\Log::class);
    }
}