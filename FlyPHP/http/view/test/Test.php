<?php namespace fly\view\test;

use fly\http\view\CompilerMain;
use fly\http\view\FileEngine;
use fly\http\view\CompilerEngine;


/**
 * 测试类
 * Class Test
 * @package fly\view\test
 */
class Test
{
    public static function index()
    {
        $compiler = new CompilerMain(new FileEngine(__DIR__ . '/tpl/', __DIR__.'/cache'), new CompilerEngine());
        try {
            $result = $compiler->render('index', ['i'=>1]);
            $compiler->flush();
            return $result;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}