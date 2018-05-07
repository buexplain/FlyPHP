<?php namespace fly\contracts\http;

interface App
{
    /**
     * 构造函数
     * App constructor.
     * @param Kernel $kernel
     * @param string $basePath
     */
    public function __construct(Kernel $kernel, $basePath);

    /**
     * 返回$this
     * @return $this
     */
    public static function getInstance();

    /**
     * 返回各种目录地址
     * @param string $pathName
     * @return string
     */
    public function getPath($pathName);

    /**
     * 返回当前请求的路由
     * @return \fly\contracts\router\Route|null
     */
    public function getCurrentRoute();

    /**
     * 中断请求
     * @param string $message
     * @param int $httpCode
     * @param null|Response::FORMAT_HTML|Response::FORMAT_JSON|Response::FORMAT_JSONP $format
     */
    public function abort($message, $httpCode, $format=null);

    /**
     * web框架启动入口
     */
    public function run();
}