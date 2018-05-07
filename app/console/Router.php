<?php namespace app\console;

use \fly\console\Router as BaseRouter;

class Router extends BaseRouter
{
    public function __construct()
    {
        $this->add(\app\console\commands\Example::NAME, \app\console\commands\Example::class);
    }
}