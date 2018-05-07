<?php namespace fly\cache;

use fly\contracts\cache\Cache as InterfaceCache;
use fly\contracts\redis\RedisManager;

class Redis implements InterfaceCache
{
    protected $redisManager;

    public function __construct(RedisManager $redisManager)
    {
        $this->redisManager = $redisManager;
    }

    /**
     * 写入一个值
     * @param $key
     * @param array|integer|string $value
     * @param int $sec
     * @return bool
     */
    public function set($key, $value, $sec=3600)
    {
        return $this->redisManager->set($key, $value, $sec);
    }

    /**
     * 读取一个值
     * @param $key
     * @param null $default
     * @return array|integer|string
     */
    public function get($key, $default=null)
    {
        return $this->redisManager->get($key, $default);
    }

    /**
     * 判断某个key是否存在
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return $this->redisManager->has($key);
    }

    /**
     * 删除一些key
     * @param $key
     * @return int 删除的key的数量
     */
    public function del($key)
    {
        return $this->redisManager->del($key);
    }
}