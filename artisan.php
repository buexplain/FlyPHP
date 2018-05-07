<?php

require_once __DIR__.'/vendor/autoload.php';

$__app = new \fly\console\App(new \app\console\Kernel(), __DIR__);

$__router = $__app->get(\fly\contracts\console\Router::class);

$__router->add(\fly\flyHttp\FlyHttp::NAME, \fly\flyHttp\FlyHttp::class);
$__router->add(\fly\console\command\Help::NAME, \fly\console\command\Help::class);

$response = $__app->run();

if($response instanceof \fly\contracts\console\Response) {
    $response->send();
}