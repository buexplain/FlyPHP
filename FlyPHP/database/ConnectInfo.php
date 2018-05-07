<?php namespace fly\database;

use fly\contracts\database\ConnectInfo as InterfaceConnectInfo;

/**
 * 连接数据库的信息
 * @package fly\database
 */
class ConnectInfo implements InterfaceConnectInfo
{
    protected $param = [
        'driver'=>'',
        'host'=>'',
        'port'=>'',
        'dbName'=>'',
        'charset'=>'',
        'userName'=>'',
        'password'=>'',
    ];

    protected $id;

    public function __construct(array $param)
    {
        foreach ($param as $k=>$v) {
            if(isset($this->param[$k])) {
                $this->param[$k] = $v;
            }
        }
        $this->id = md5(implode('', $this->param));
    }

    public function __toString()
    {
        return json_encode($this->param);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDriver()
    {
        return $this->param['driver'];
    }

    public function getHost()
    {
        return $this->param['host'];
    }

    public function getPort()
    {
        return $this->param['port'];
    }

    public function getDbName()
    {
        return $this->param['dbName'];
    }

    public function getCharset()
    {
        return $this->param['charset'];
    }

    public function getUserName()
    {
        return $this->param['userName'];
    }

    public function getPassword()
    {
        return $this->param['password'];
    }
}