<?php namespace fly\database;

use fly\database\builder\MysqlFactory;
use PDO;
use fly\contracts\database\ConnectInfo as InterfaceConnectInfo;
use Exception;
use fly\contracts\database\FlyPDO as InterfaceFlyPDO;


/**
 * 数据库操作类
 * @package fly\database
 */
class FlyPDO implements InterfaceFlyPDO
{
    /**
     * @var PDO
     */
    protected $pdo;

    /**
     * 连接信息
     * @var InterfaceConnectInfo
     */
    protected $connectInfo;

    /**
     * PDO 相关配置
     * @var array
     */
    protected $options = [
        //指定连接超时的秒数
        PDO::ATTR_TIMEOUT=>1
    ];

    /**
     * @var \fly\contracts\database\builder\Factory
     */
    protected $factory;

    /**
     * FlyPDO constructor.
     * @param InterfaceConnectInfo $connectInfo
     */
    public function __construct(InterfaceConnectInfo $connectInfo)
    {
        $this->connectInfo = $connectInfo;
        $this->createFactory();
        $this->connect();
    }

    /**
     * 根据不同的数据库驱动创建不同的sql构造器工厂
     * @throws Exception
     */
    protected function createFactory()
    {
        switch ($this->connectInfo->getDriver()) {
            case 'mysql':
                $this->factory = new MysqlFactory();
                break;
            default:
                throw new Exception('Database driver error');
                break;
        }
    }

    /**
     * 连接数据库
     */
    protected function connect()
    {
        $dsn = "{$this->connectInfo->getDriver()}:host={$this->connectInfo->getHost()};port={$this->connectInfo->getPort()};dbname={$this->connectInfo->getDbName()}";
        try{
            $this->pdo = new PDO($dsn, $this->connectInfo->getUserName(), $this->connectInfo->getPassWord(), $this->options);
            $this->pdo->exec("set names {$this->connectInfo->getCharset()}");
        }catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * 返回新增语句的构造器
     * @return \fly\contracts\database\builder\Insert
     */
    public function insert()
    {
        return $this->factory->createInsert($this);
    }

    /**
     * 返回删除语句的构造器
     * @return \fly\contracts\database\builder\Delete
     */
    public function delete()
    {
        return $this->factory->createDelete($this);
    }

    /**
     * 返回更新语句的构造器
     * @return \fly\contracts\database\builder\Update
     */
    public function update()
    {
        return $this->factory->createUpdate($this);
    }

    /**
     * 返回查询语句的构造器
     * @return \fly\contracts\database\builder\Select
     */
    public function select()
    {
        return $this->factory->createSelect($this);
    }

    /**
     * 启动一个事务
     * @return bool
     * @throws Exception
     */
    public function beginTransaction()
    {
        retry:
        try{
            return $this->pdo->beginTransaction();
        }catch (Exception $e) {
            $errorInfo = $this->pdo->errorInfo();
            if($this->checkReconnect($errorInfo[1])) {
                $this->connect();
                goto retry;
            }else{
                throw $e;
            }
        }
    }

    /**
     * 回滚一个事务
     * @return bool
     */
    public function rollBack()
    {
        return $this->pdo->rollBack();
    }

    /**
     * 提交一个事务
     * @return bool
     */
    public function commit()
    {
        return $this->pdo->commit();
    }

    /**
     * 检查是否在一个事务内
     * @return bool
     * @throws Exception
     */
    public function inTransaction()
    {
        retry:
        try{
            return $this->pdo->inTransaction();
        }catch (Exception $e) {
            $errorInfo = $this->pdo->errorInfo();
            if($this->checkReconnect($errorInfo[1])) {
                $this->connect();
                goto retry;
            }else{
                throw $e;
            }
        }
    }

    /**
     * 预编译的方式执行一条sql语句
     * @param $sql
     * @param array ...$param
     * @return Response
     * @throws Exception
     */
    public function execute($sql, ...$param)
    {
        $param = isset($param[0]) && is_array($param[0]) ? $param[0] : $param;
        $sth = null;
        retry:
        try{
            $sth = $this->pdo->prepare($sql);
            $sth->execute($param);
            return new Response($sth, $this->pdo->lastInsertId());
        }catch (Exception $e) {
            $errorInfo = $sth->errorInfo();
            if($this->checkReconnect($errorInfo[1])) {
                $this->connect();
                goto retry;
            }else{
                throw $e;
            }
        }
    }

    /**
     * 检查sql server 错误代码 判断是否重新连接数据库
     * @param $code
     * @return bool
     */
    protected function checkReconnect($code)
    {
        if($this->connectInfo->getDriver() == 'mysql') {
            if($code == 2006 || $code == 2013) {
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}