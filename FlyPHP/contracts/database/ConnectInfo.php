<?php namespace fly\contracts\database;

/**
 * 连接数据库的信息
 * @package fly\contracts\database
 */
interface ConnectInfo
{
    public function __construct(array $param);
    public function __toString();
    public function getId();
    public function getDriver();
    public function getHost();
    public function getPort();
    public function getDbName();
    public function getCharset();
    public function getUserName();
    public function getPassword();
}