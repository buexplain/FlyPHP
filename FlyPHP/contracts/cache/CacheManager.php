<?php namespace fly\contracts\cache;

interface CacheManager extends Cache
{
    /** 切换到某个驱动
     * @param string $key file|redis
     * @return Cache
     */
    public function change($key);
}