<?php namespace fly\http\session;

use fly\contracts\redis\RedisManager;
use fly\contracts\config\Config;
use Exception;
use fly\contracts\http\Response;
use fly\contracts\http\Request;
use fly\http\App;

class SessionFactory
{
    public static function create(App $container)
    {
        $config = $container->get(Config::class)->get('session');
        switch ($config['driver']) {
            case 'file':
                return new File($config, $container->get(Request::class), $container->get(Response::class), $container->getPath('session'));
                break;
            case 'redis':
                return new Redis($config, $container->get(Request::class), $container->get(Response::class), $container->get(RedisManager::class));
                break;
            default:
                throw new Exception('session driver error');
        }
    }
}