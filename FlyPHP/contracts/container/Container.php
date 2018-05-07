<?php namespace fly\contracts\container;

/**
 * 容器接口
 * @package fly\contracts\container
 */
interface Container
{
    /**
     * 删除绑定的实例
     * @param string $abstract
     */
    public function del($abstract);

    /**
     * 判断实例是否存在
     * @param string $abstract
     * @return bool
     */
    public function has($abstract);

    /**
     * 绑定到容器
     * @param string $abstract
     * @param null|\Closure|object|string $concrete
     * @param bool $share 是否为单例模式
     * @return $this
     */
    public function set($abstract, $concrete=null, $share=true);

    /**
     * 通过容器得到一个对象
     * @param string $abstract
     * @param array $paramArr 对象初始化的时候需要的参数
     * @return mixed|object
     */
    public function get($abstract, array $paramArr=[]);

    /**
     * 情景绑定
     * @param string $when
     * @param string $need
     * @param string $give
     */
    public function setContext($when, $need, $give);

    /**
     * 获取一个方法的参数
     * @param string $className
     * @param string $methodName
     * @param array $paramArr
     * @return array
     */
    public function getMethodParam($className, $methodName, array $paramArr=[]);

    /**
     * 获取一个函数的参数
     * @param string $functionName
     * @param array $paramArr
     * @return array
     */
    public function getFunctionParam($functionName, array $paramArr=[]);
}