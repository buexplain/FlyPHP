<?php namespace app\console;

use fly\contracts\console\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    protected $provider = [
        \fly\provider\Config::class,
        \fly\provider\console\Request::class,
        \fly\provider\console\Response::class,
        \app\console\provider\Router::class,
//        \fly\provider\DB::class,
    ];
}