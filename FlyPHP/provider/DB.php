<?php namespace fly\provider;

use fly\contracts\provider\Provider;

class DB extends Provider
{
    public function register()
    {
        $this->app->set(\fly\contracts\database\DB::class, function ($app) {
            return new \fly\database\DB($app->get(\fly\contracts\config\Config::class)->get('database'));
        });
    }
}