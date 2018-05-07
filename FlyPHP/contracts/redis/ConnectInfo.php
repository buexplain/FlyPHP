<?php namespace fly\contracts\redis;

/**
 * redis 连接信息 接口
 * @package fly\contracts\redis
 */
interface ConnectInfo
{
    public function __toString();
    public function getId();
    public function getHost();
    public function getPort();
    public function getPassword();
    public function getTimeout();
    public function getSelect();
}