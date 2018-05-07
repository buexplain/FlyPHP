<?php namespace fly\contracts\redis;

/**
 * redis 客户端接口
 * @package fly\contracts\redis
 */
interface Redis
{
    /**
     * 写入一个值
     * @param $key
     * @param array|integer|string $value
     * @param int $sec
     * @return bool
     */
    public function set($key, $value, $sec=3600);

    /**
     * 读取一个值
     * @param $key
     * @param null $default
     * @return array|integer|string
     */
    public function get($key, $default=null);

    /**
     * 判断某个key是否存在
     * @param $key
     * @return bool
     */
    public function has($key);

    /**
     * 删除一些key
     * @param $key1
     * @param null $key2
     * @param null $key3
     * @return int 删除的key的数量
     */
    public function del($key1, $key2=null, $key3=null);

    /**
     * 自增缓存
     * @param string $key
     * @param int $step
     * @return int 自增后的值
     */
    public function inc($key, $step=1);

    /**
     * 自减缓存
     * @param string $key
     * @param int $step
     * @return int 自减后的值
     */
    public function dec($key, $step=1);

    /**
     * 代理 redis 的方法
     * @link http://doc.redisfans.com/
     * @param $method
     * @param $args
     * @return mixed
     */
    public function __call($method, $args);
}