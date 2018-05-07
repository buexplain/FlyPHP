<?php namespace fly\contracts\http;

use Exception;

abstract class Facade
{
    public static function __callStatic($method, $args)
    {
        $instance = static::getFacadeClass();

        if (!$instance) {
            throw new Exception('A facade root has not been set.');
        }

        return $instance->$method(...$args);
    }

    protected static function getFacadeClass()
    {
        return null;
    }
}