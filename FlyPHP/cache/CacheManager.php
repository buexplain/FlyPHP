<?php namespace fly\cache;

use fly\contracts\redis\RedisManager;
use fly\contracts\cache\CacheManager as InterfaceCacheManager;
use fly\contracts\container\Container;
use fly\contracts\config\Config;
use Exception;
use fly\contracts\cache\Cache;

/**
 * cache 管理类
 * @package fly\cache
 */
class CacheManager implements InterfaceCacheManager
{
    protected $handlerArr = [];
    protected $container;
    protected $config;
    protected $handler;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->config = $container->get(Config::class)->get('cache');
        $this->handler = $this->createHandler($this->config['driver']);
    }

    /**
     * 创建一个cache的驱动
     * @return Cache
     * @throws Exception
     */
    protected function createHandler($key)
    {
        if(!isset($this->handlerArr[$key])) {
            switch ($key) {
                case 'file':
                    $this->handlerArr[$key] = new File($this->container->getPath('cache'));
                    break;
                case 'redis':
                    $this->handlerArr[$key] = new Redis($this->container->get(RedisManager::class));
                    break;
                default:
                    throw new Exception('cache driver error');
            }
        }
        return $this->handlerArr[$key];
    }

    /** 切换到某个驱动
     * @param string $key file|redis
     * @return Cache
     */
    public function change($key)
    {
        return $this->createHandler($key);
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
        return $this->handler->set($key, $value, $sec);
    }

    /**
     * 读取一个值
     * @param $key
     * @param null $default
     * @return array|integer|string
     */
    public function get($key, $default=null)
    {
        return $this->handler->get($key, $default);
    }

    /**
     * 判断某个key是否存在
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return $this->handler->has($key);
    }

    /**
     * 删除一些key
     * @param $key
     * @return int 删除的key的数量
     */
    public function del($key)
    {
        return $this->handler->del($key);
    }
}