<?php

use fly\contracts\http\Facade;
use fly\http\App;

/**
 * @method static array getParams()
 * @method static string getController()
 * @method static string getAction()
 * @method static setController($controller)
 * @method static setAction($action)
 * @method static \fly\contracts\router\Route middleware($className)
 * @method static getMiddleware()
 */
class Route extends Facade
{
    protected static function getFacadeClass()
    {
        return App::getInstance()->getCurrentRoute();
    }
}