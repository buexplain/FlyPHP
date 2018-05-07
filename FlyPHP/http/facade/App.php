<?php
use fly\contracts\http\Facade;
use fly\http\App as HttpApp;

/**
 * @method static del($abstract)
 * @method static bool has($abstract)
 * @method static \fly\contracts\container\Container set($abstract, $concrete=null, $share=true)
 * @method static mixed|object get($abstract, array $paramArr=[])
 * @method static setContext($when, $need, $give)
 * @method static array getMethodParam($className, $methodName, array $paramArr=[])
 * @method static array getFunctionParam($functionName, array $paramArr=[])
 * @method static string getPath($pathName)
 * @method static \fly\contracts\router\Route|null getCurrentRoute()
 * @method static abort($message, $httpCode, $format=null);
 */
class App extends Facade
{
    protected static function getFacadeClass()
    {
        return HttpApp::getInstance();
    }
}