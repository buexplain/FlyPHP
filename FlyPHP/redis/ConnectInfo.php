<?php namespace fly\redis;

use fly\contracts\redis\ConnectInfo as InterfaceConnectInfo;

/**
 * redis连接信息
 * @package fly\redis
 */
class ConnectInfo implements InterfaceConnectInfo
{
    protected $param = [
        'host'=>'',
        'port'=>'',
        'password'=>'',
        'timeout'=>0.3,
        'select'=>0,
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

    public function getHost()
    {
        return $this->param['host'];
    }

    public function getPort()
    {
        return $this->param['port'];
    }

    public function getPassword()
    {
        return $this->param['password'];
    }

    public function getTimeout()
    {
        return $this->param['timeout'];
    }

    public function getSelect()
    {
        return $this->param['select'];
    }
}