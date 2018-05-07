<?php namespace fly\contracts\console;

abstract class Kernel
{
    protected $provider = [];

    protected $middleware = [];

    public function getProvider()
    {
        return $this->provider;
    }

    public function getMiddleware()
    {
        return $this->middleware;
    }
}