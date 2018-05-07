<?php namespace fly\database;

use fly\contracts\database\ConnectInfo as InterfaceConnectInfo;
use fly\contracts\database\DB as InterfaceDB;
use Exception;

/**
 * FlyPDO的代理类
 * @package fly\database
 */
class DB implements InterfaceDB
{
    /**
     * 主库配置表
     * @var array
     */
    protected $masterParam = [];

    /**
     * 从库配置表
     * @var array
     */
    protected $slaveParam  = [];

    /**
     * 主库FlyPDO池
     * @var array
     */
    protected $masterFlyPDO = [];

    /**
     * 从库FlyPDO池
     * @var array
     */
    protected $slaveFlyPDO = [];

    /**
     * 默认的主库FlyPDO
     * @var null|\fly\database\FlyPDO
     */
    protected $defaultMasterFlyPDO=null;

    /**
     * 默认的从库FlyPDO
     * @var null|\fly\database\FlyPDO
     */
    protected $defaultSlaveFlyPDO=null;

    /**
     * DB constructor.
     * @param array $param 数据库配置
     */
    public function __construct(array $param)
    {
        $this->setParam($param);
    }

    /**
     * 设置连接属性
     * @param $param
     * @throws Exception
     */
    protected function setParam(array $param)
    {
        if(isset($param['master'])) {
            $param['master'] = (array)$param['master'];
            foreach ($param['master'] as $k => $v) {
                $this->masterParam[$k] = new ConnectInfo((array)$v);
            }
        }

        if(isset($param['slave'])) {
            $param['slave'] = (array) $param['slave'];
            foreach ($param['slave'] as $k=>$v) {
                $this->slaveParam[$k] = new ConnectInfo((array) $v);
            }
        }

        if(count($this->masterParam) == 0) {
            throw new Exception('database config error');
        }

        //随机选择一个主库
        $master = $this->masterParam;
        shuffle($master);
        $this->defaultMasterFlyPDO = $this->getMasterFlyPDO($master[0]);

        //随机选择一个从库
        if(count($this->slaveParam)) {
            $slave = $this->slaveParam;
            shuffle($slave);
            $this->defaultSlaveFlyPDO = $this->getSlaveFlyPDO($slave[0]);
        }
    }

    /**
     * 返回一个主数据库 FlyPDO
     * @param InterfaceConnectInfo $connectInfo
     * @return FlyPDO
     */
    protected function getMasterFlyPDO(InterfaceConnectInfo $connectInfo)
    {
        $k = $connectInfo->getId();
        if(!isset($this->masterFlyPDO[$k])) {
            $this->masterFlyPDO[$k] = new FlyPDO($connectInfo);
        }
        return $this->masterFlyPDO[$k];
    }

    /**
     * 返回一个从数据库 FlyPDO
     * @param InterfaceConnectInfo $connectInfo
     * @return FlyPDO
     */
    protected function getSlaveFlyPDO(InterfaceConnectInfo $connectInfo)
    {
        $k = $connectInfo->getId();
        if(!isset($this->slaveFlyPDO[$k])) {
            $this->slaveFlyPDO[$k] = new FlyPDO($connectInfo);
        }
        return $this->slaveFlyPDO[$k];
    }

    /**
     * 返回某个主库 FlyPDO
     * @param null|mixed $key
     * @return \fly\database\FlyPDO
     * @throws Exception
     */
    public function master($key=null)
    {
        if(is_null($key)) {
            //默认的主库
            $result = $this->defaultMasterFlyPDO;
        }else{
            //指定的主库
            if(!isset($this->masterParam[$key])) {
                throw new Exception('master database config index error');
            }
            $result =  $this->getMasterFlyPDO($this->masterParam[$key]);
        }
        return $result;
    }

    /**
     * 返回某个从库 FlyPDO
     * @param null|mixed $key
     * @return \fly\database\FlyPDO
     * @throws Exception
     */
    public function slave($key=null)
    {
        if(is_null($key)) {
            //默认的从库
            $result = $this->defaultSlaveFlyPDO;
        }else{
            //指定的从库
            if(!isset($this->slaveParam[$key])) {
                throw new Exception('slave database config index error');
            }
            $result =  $this->getSlaveFlyPDO($this->slaveParam[$key]);
        }
        return $result;
    }

    /**
     * @return \fly\contracts\database\builder\Insert
     */
    public function insert()
    {
        return $this->master()->insert();
    }

    /**
     * @return \fly\contracts\database\builder\Delete
     */
    public function delete()
    {
        return $this->master()->delete();
    }

    /**
     * @return \fly\contracts\database\builder\Update
     */
    public function update()
    {
        return $this->master()->update();
    }

    /**
     * @return \fly\contracts\database\builder\Select
     */
    public function select()
    {
        if(is_null($this->defaultSlaveFlyPDO)) {
            return $this->master()->select();
        }else{
            return $this->slave()->select();
        }
    }

    /**
     * 启动一个事务
     * @return bool
     */
    public function beginTransaction()
    {
        return $this->master()->beginTransaction();
    }

    /**
     * 回滚一个事务
     * @return bool
     */
    public function rollBack()
    {
        return $this->master()->rollBack();
    }

    /**
     * 提交一个事务
     * @return bool
     */
    public function commit()
    {
        return $this->master()->commit();
    }

    /**
     * 检查是否在一个事务内
     * @return bool
     */
    public function inTransaction()
    {
        return $this->master()->inTransaction();
    }

    /**
     * 预编译的方式执行一条sql语句
     * @param string
     * @param array ...$param
     * @return Response
     */
    public function execute($sql, ...$param)
    {
        if(is_null($this->defaultSlaveFlyPDO)) {
            //没有从库 走主库
            return $this->master()->execute($sql, $param);
        }else{
            //存在从库
            $sql = ltrim($sql);
            //判断sql类型
            if(strtoupper(substr($sql, 0, 6)) == 'SELECT') {
                //查询语句走从库
                return $this->slave()->execute($sql, $param);
            }else{
                //非查询语句走主库
                return $this->master()->execute($sql, $param);
            }
        }
    }
}