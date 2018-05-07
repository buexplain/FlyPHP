<?php namespace fly\redis;

use fly\contracts\redis\Redis as InterfaceRedis;
use Exception;
use Redis as RedisExtension;
use fly\contracts\redis\ConnectInfo as InterfaceConnectInfo;
use RedisException;

/**
 * redis客户端
 * @link https://github.com/phpredis/phpredis
 * @package fly\redis
 */
class Redis implements InterfaceRedis
{
    /**
     * @var InterfaceConnectInfo
     */
    protected $connectInfo;

    /**
     * @var RedisExtension
     */
    protected $redis;

    public function __construct(InterfaceConnectInfo $connectInfo)
    {
        if (!extension_loaded('redis')) {
            throw new Exception('not support: redis');
        }
        $this->connectInfo = $connectInfo;
        $this->connect();
    }

    /**
     * 连接redis
     * @throws Exception
     */
    protected function connect()
    {
        try{
            $this->redis = new RedisExtension();
            $this->redis->connect($this->connectInfo->getHost(), $this->connectInfo->getPort(), $this->connectInfo->getTimeout());
            if(strlen($this->connectInfo->getPassword()) > 0) {
                $this->redis->auth($this->connectInfo->getPassword());
            }
            $this->redis->select($this->connectInfo->getSelect());
        }catch (RedisException $e) {
            //再抛出一个异常避免密码泄漏
            throw new Exception($e->getMessage());
        }
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
        $value = is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) : $value;
        $sec = (integer) $sec;
        retry:
        try{
            if ($sec > 0) {
                $result = $this->redis->setex($key, $sec, $value);
            } else {
                $result = $this->redis->set($key, $value);
            }
            return $result;
        }catch (RedisException $e) {
            $this->connect();
            goto retry;
        }
    }

    /**
     * 读取一个值
     * @param $key
     * @param null $default
     * @return array|integer|string
     */
    public function get($key, $default=null)
    {
        retry:
        try{
            $value = $this->redis->get($key);
            if (is_null($value)) {
                return $default;
            }
            $result = json_decode($value, true);
            return (null === $result) ? $value : $result;
        }catch (RedisException $e) {
            $this->connect();
            goto retry;
        }
    }

    /**
     * 判断某个key是否存在
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        retry:
        try{
            return $this->redis->get($key) ? true : false;
        }catch (RedisException $e) {
            $this->connect();
            goto retry;
        }
    }

    /**
     * 删除一些key
     * @param $key1
     * @param null $key2
     * @param null $key3
     * @return int 删除的key的数量
     */
    public function del($key1, $key2=null, $key3=null)
    {
        retry:
        try{
            return $this->redis->del($key1, $key2, $key3);
        }catch (RedisException $e) {
            $this->connect();
            goto retry;
        }
    }

    /**
     * 自增缓存
     * @param string $key
     * @param int $step
     * @return int 自增后的值
     */
    public function inc($key, $step=1)
    {
        retry:
        try{
            return $this->redis->incrby($key, $step);
        }catch (RedisException $e) {
            $this->connect();
            goto retry;
        }
    }

    /**
     * 自减缓存
     * @param string $key
     * @param int $step
     * @return int 自减后的值
     */
    public function dec($key, $step=1)
    {
        retry:
        try{
            return $this->redis->decrby($key, $step);
        }catch (RedisException $e) {
            $this->connect();
            goto retry;
        }
    }

    /**
     * 代理 redis 的方法
     * @param $method
     * @param $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        retry:
        try{
            switch (count($args))
            {
                case 0:
                    return $this->redis->$method();
                case 1:
                    return $this->redis->$method($args[0]);
                case 2:
                    return $this->redis->$method($args[0], $args[1]);
                case 3:
                    return $this->redis->$method($args[0], $args[1], $args[2]);
                case 4:
                    return $this->redis->$method($args[0], $args[1], $args[2], $args[3]);
                default:
                    return call_user_func_array(array($this->redis, $method), $args);
            }
        }catch(RedisException $e) {
            $this->connect();
            goto retry;
        }
    }
}