<?php namespace fly\contracts\console;

/**
 * 控制台app类接口
 * @package fly\contracts\console
 */
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
     * console框架启动入口
     */
    public function run();
}