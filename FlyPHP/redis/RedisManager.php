<?php namespace fly\redis;

use fly\contracts\redis\RedisManager as InterfaceRedisManager;
use fly\contracts\redis\ConnectInfo as InterfaceConnectInfo;
use Exception;
use fly\contracts\redis\Redis as InterfaceRedis;

/**
 * redis 客户端管理类
 * @package fly\redis
 */
 class RedisManager implements InterfaceRedisManager
 {
     /**
      * 配置信息
      * @var array
      */
    protected $param = [];

     /**
      * @var Redis
      */
    protected $defaultRedis;

     /**
      * redis服务器池
      * @var array
      */
    protected $redis = [];

    public function __construct(array $param)
    {
        $this->setParam($param);
    }

    protected function setParam(array $param)
    {
        foreach ($param as $k=>$v) {
            $this->param[$k] = new ConnectInfo((array) $v);
        }

        if(count($this->param) == 0) {
            throw new Exception('redis config error');
        }

        //默认操作第一个redis服务
        $tmp = array_values($this->param);
        $this->defaultRedis = $this->getRedis($tmp[0]);
    }

     /**
      * 创建一个redis
      * @param InterfaceConnectInfo $connectInfo
      * @return InterfaceRedis
      */
    protected function getRedis(InterfaceConnectInfo $connectInfo)
    {
        $k = $connectInfo->getId();
        if(!isset($this->redis[$k])) {
            $this->redis[$k] = new Redis($connectInfo);
        }
        return $this->redis[$k];
    }

     /**
      * 切换到某个redis客户端
      * @param string|integer $key redis配置文件的key值
      * @return InterfaceRedis
      * @throws Exception
      */
    public function change($key)
    {
        if(!isset($this->param[$key])) {
            throw new Exception('redis config index error');
        }
        return $this->getRedis($this->param[$key]);
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
        return $this->defaultRedis->set($key, $value, $sec);
     }

     /**
      * 读取一个值
      * @param $key
      * @param null $default
      * @return array|integer|string
      */
     public function get($key, $default=null)
     {
        return $this->defaultRedis->get($key, $default);
     }

     /**
      * 判断某个key是否存在
      * @param $key
      * @return bool
      */
     public function has($key)
     {
        return $this->defaultRedis->has($key);
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
         return $this->defaultRedis->del($key1, $key2, $key3);
     }

     /**
      * 自增缓存
      * @param string $key
      * @param int $step
      * @return int 自增后的值
      */
     public function inc($key, $step=1)
     {
        return $this->defaultRedis->inc($key, $step);
     }

     /**
      * 自减缓存
      * @param string $key
      * @param int $step
      * @return int 自减后的值
      */
     public function dec($key, $step=1)
     {
        return $this->defaultRedis->dec($key, $step);
     }

     /**
      * 代理 redis 的方法
      * @param $method
      * @param $args
      * @return mixed
      */
     public function __call($method, $args)
     {
         return call_user_func_array(array($this->defaultRedis, $method), $args);
     }
 }