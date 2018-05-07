<?php

require_once realpath(__DIR__.'/../vendor/autoload.php');
$__app = new \fly\http\App(new \app\http\Kernel(), realpath(__DIR__.DIRECTORY_SEPARATOR.'..'));
$__app->run();

//date_default_timezone_set('PRC'); //设置中国时区
//
//define('BASE_PATH', realpath(__DIR__.DIRECTORY_SEPARATOR.'..'));
//function __autoload($class)
//{
//    $class = ltrim($class, '\\');
//    if(substr($class, 0, 3) == 'fly') {
//        $class = BASE_PATH.'/vendor/'.$class.'.php';
//    }elseif(substr($class, 0, 3) == 'app') {
//        $class = BASE_PATH.'/'.$class.'.php';
//    }
//    include $class;
//}
//
//
//xhprof_enable(XHPROF_FLAGS_CPU+XHPROF_FLAGS_MEMORY);
//xhprof_enable();
//include_once realpath(__DIR__.DIRECTORY_SEPARATOR.'..').'/vendor/fly/helper.php';
//
////require_once realpath(__DIR__.'/../vendor/autoload.php');
//
//$__app = new \fly\http\App(new \app\http\Kernel(), realpath(__DIR__.DIRECTORY_SEPARATOR.'..'));
//$__app->run();
//
//$xhprof_data = xhprof_disable();
//include_once "E:\xhprof\xhprof-0.9.4\xhprof_lib\utils\xhprof_lib.php";
//include_once "E:\xhprof\xhprof-0.9.4\xhprof_lib\utils\xhprof_runs.php";
//// save raw data for this profiler run using default
//// implementation of iXHProfRuns.
//$xhprof_runs = new XHProfRuns_Default();
//// save the run under a namespace "xhprof_foo"
//$run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_flyphp"); //导出性能日志 到你安装扩展时候指定的文件夹里面
//