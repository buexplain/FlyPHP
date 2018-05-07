<?php namespace fly\provider\http;

use fly\contracts\provider\Provider;
use fly\http\view\FileEngine;
use fly\http\view\CompilerEngine;
use fly\http\view\CompilerMain;

class View extends Provider
{
    public function register()
    {
        $app = $this->app;
        $this->app->set(\fly\contracts\http\View::class, function () use(&$app) {
            return new CompilerMain(new FileEngine($app->getPath('view'), $app->getPath('viewCache')), new CompilerEngine());
        });
    }
}