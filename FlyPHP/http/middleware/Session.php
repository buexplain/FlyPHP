<?php namespace fly\http\middleware;

use fly\http\App;
use fly\contracts\http\Session as InterfaceSession;

/**
 * session 中间件
 * @package fly\middleware
 */
class Session extends \fly\contracts\http\Middleware
{
    public function before(\fly\contracts\http\Request $request, \Closure $next)
    {
        App::getInstance()->get(InterfaceSession::class);
        return $next($request);
    }

    public function after(\fly\contracts\http\Response $response, \Closure $next)
    {
        App::getInstance()->get(InterfaceSession::class)->save();
        App::getInstance()->del(InterfaceSession::class);
        return $next($response);
    }
}