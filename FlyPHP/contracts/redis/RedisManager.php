<?php namespace fly\contracts\redis;


/**
 * redis 客户端管理接口
 * @package fly\contracts\redis
 */
interface RedisManager extends Redis
{
    /** 切换到某个 redis 客户端
     * @param string|integer $key redis配置文件的key值
     * @return Redis
     */
    public function change($key);
}