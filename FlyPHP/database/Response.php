<?php namespace fly\database;

use PDO;
use PDOStatement;
use Exception;
use fly\contracts\database\Response as InterfaceResponse;

/**
 * 数据库操作结果响应对象
 * @package fly\database
 */
class Response implements InterfaceResponse
{
    protected $errorInfo;
    protected $lastInsertId;
    protected $rowCount;
    protected $data = [];

    public function __construct(PDOStatement $sth, $lastInsertId)
    {
        $this->errorInfo = $sth->errorInfo();
        if(!is_null($this->errorInfo[2])) {
            throw new Exception($this->errorInfo[2]);
        }
        $this->lastInsertId = $lastInsertId;
        $this->rowCount = $sth->rowCount();
        while (($row = $sth->fetch(PDO::FETCH_OBJ))) {
            $this->data[] = $row;
        }
        unset($sth);
    }

    /**
     * 返回自增id
     * @return integer
     */
    public function getInsertId()
    {
        return $this->lastInsertId;
    }

    /**
     * 返回受影响的行数
     * @return int
     */
    public function getRowCount()
    {
        return $this->rowCount;
    }

    /**
     * 取回数据表的第一条数据
     * @return array|null
     */
    public function first()
    {
        return isset($this->data[0]) ? $this->data[0] : null;
    }

    /**
     * 取回所有数据
     * @return array|null
     */
    public function get()
    {
        return count($this->data) > 0 ? $this->data : null;
    }

    /**
     * 从单行中取出单列数据
     * @param $key
     * @return mixed|null
     */
    public function pluck($key)
    {
        return isset($this->data[0]->{$key}) ? $this->data[0]->{$key} : null;
    }

    /**
     * 取多行数据的「列数据」数组
     * @param $key
     * @param null $value
     * @return array|null
     */
    public function lists($key, $value=null)
    {
        $result = [];
        if(is_null($value)) {
            foreach ($this->data as $k=>$v) {
                $result[] = $v->{$key};
            }
        }else{
            foreach ($this->data as $k=>$v) {
                $result[$v->{$key}] = $v->{$value};
            }
        }
        if(count($result) == 0) {
            return null;
        }else{
            return $result;
        }
    }

    /**
     * 根据给定的键进行分组
     * @param $key
     * @return array|null
     */
    public function groupBy($key)
    {
        $result = [];
        foreach ($this->data as $k=>$v) {
            if(!isset($result[$v->{$key}])) {
                $result[$v->{$key}] = [];
            }else{
                $result[$v->{$key}][] = $v;
            }
        }
        return count($result) == 0 ? null : $result;
    }

    /**
     * 以给定键的值作为返回数组的键
     * @param $key
     * @return array|null
     */
    public function keyBy($key)
    {
        $result = [];
        foreach ($this->data as $k=>$v) {
            $result[$v->{$key}] = $v;
        }
        return count($result) == 0 ? null : $result;
    }

    /**
     * 按给定的键的值进行排序
     * @param $key
     * @param int $direction
     * @param int $type
     * @return array
     */
    public function sortBy($key, $direction=SORT_ASC, $type=SORT_NUMERIC)
    {
        $keys = [];
        foreach($this->data as $v) {
            $keys[] = $v->{$key};
        }
        $result = $this->data;
        array_multisort($keys, $direction, $type, $result);
        return $result;
    }
}