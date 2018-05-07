<?php namespace fly\contracts\http;

use Closure;

abstract class Middleware
{
    const BEFORE = 'before';
    const AFTER = 'after';

    abstract public function before(Request $request, Closure $next);

    public function after(Response $response, Closure $next)
    {
        return $next($response);
    }
}