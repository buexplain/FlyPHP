<?php namespace fly\provider\http;

use fly\contracts\provider\Provider;
use fly\contracts\http\Session as InterfaceSession;
use fly\http\session\SessionFactory;

/**
 * session供应商
 * @package fly\provider\http
 */
class Session extends Provider
{
    public function register()
    {
        $this->app->set(InterfaceSession::class, function($app) {
            return SessionFactory::create($app);
        });
    }
}