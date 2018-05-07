<?php namespace fly\contracts\cache;

interface Cache
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
     * @param $key
     * @return int 删除的key的数量
     */
    public function del($key);
}