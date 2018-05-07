<?php namespace app\http\middleware;

use fly\contracts\http\Middleware;

class Auth extends Middleware
{
    public function before(\fly\contracts\http\Request $request, \Closure $next)
    {
        return $next($request);
    }

    public function after(\fly\contracts\http\Response $response, \Closure $next)
    {
        return $next($response);
    }
}