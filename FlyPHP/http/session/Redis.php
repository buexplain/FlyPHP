<?php namespace fly\http\session;

use fly\contracts\http\Session;
use fly\contracts\http\Response;
use fly\contracts\http\Request;
use fly\contracts\redis\RedisManager;

/**
 * session redis驱动
 * @package fly\session
 */
class Redis extends Session
{
    /**
     * redis操作对象
     * @var RedisManager
     */
    protected $redisManager;

    protected $redisKey;

    public function __construct(array $config, Request $request,Response $response, RedisManager $redisManager)
    {
        parent::__construct($config, $request, $response);
        $this->redisManager = $redisManager;
        $this->setParam($request->cookie($this->config['name']));
        $this->initData();
    }

    /**
     * 设置参数
     * @param $sid
     */
    protected function setParam($sid)
    {
        $this->sid = is_null($sid) ? $this->createSid() : $sid;
        $this->redisKey = $this->config['name'].':'.$this->sid;
    }

    /**
     * 初始化data数据
     */
    protected function initData()
    {
        $this->data = $this->redisManager->get($this->redisKey);
        $this->data = $this->data === false ? [] : unserialize($this->data);
    }

    /**
     * 重置一个session id
     */
    public function regenerate()
    {
        $this->redisManager->del($this->redisKey);
        $this->setParam($this->createSid());
    }

    /**
     * 清空session
     */
    public function clear()
    {
        $this->redisManager->del($this->redisKey);
        $this->response->setCookie(
            $this->config['name'],
            $this->sid,
            0-$this->config['expire'],
            $this->config['path'],
            $this->config['domain'],
            $this->config['secure'],
            $this->config['httpOnly']
        );
        $this->data = null;
    }

    /**
     * 保存session
     */
    public function save()
    {
        if(!is_null($this->data)) {
            $this->response->setCookie(
                $this->config['name'],
                $this->sid,
                $this->config['expire'],
                $this->config['path'],
                $this->config['domain'],
                $this->config['secure'],
                $this->config['httpOnly']
            );
            $this->redisManager->set($this->redisKey, serialize($this->data), $this->config['expire']);
        }
    }
}