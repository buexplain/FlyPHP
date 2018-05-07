<?php namespace fly\contracts\log;

interface Log
{
    //错误级别
    const LEVEL_ERROR = 1;
    //警告级别
    const LEVEL_WARNING = 2;
    //信息级别
    const LEVEL_INFO = 3;
    //调试级别
    const LEVEL_DEBUG = 4;

    public function error($message, $tag=null);
    public function warning($message, $tag=null);
    public function info($message, $tag=null);
    public function debug($message, $tag=null);
}