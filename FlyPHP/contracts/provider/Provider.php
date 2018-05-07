<?php namespace fly\contracts\provider;

abstract class Provider
{
    /**
     * @var \fly\contracts\container\Container
     */
    protected $app;

    public function __construct(\fly\contracts\container\Container $app)
    {
        $this->app = $app;
    }

    abstract public function register();
}