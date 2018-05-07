<?php namespace fly\database\builder\mysql;

use fly\contracts\database\builder\Delete as AbstractDelete;

/**
 * 删除语句构造器
 * @package fly\database
 */
class Delete extends AbstractDelete
{
    use Where;

    protected $table;
    protected $data = [];
    protected $orderBy = [];
    protected $limit = [];

    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    public function orderBy($column, $direction='ASC')
    {
        $this->orderBy[] = [
            'column'=>$column,
            'direction'=>strtoupper($direction)
        ];
        return $this;
    }

    public function limit($offset, $number)
    {
        $this->limit = [
            'offset'=>$offset,
            'number'=>$number
        ];
        return $this;
    }

    public function getData()
    {
        return $this->whereValue;
    }

    public function toSql()
    {
        $where = '';
        $tmp = '';
        foreach ($this->where as $v) {
            $tmp = $v['boolean'].' ';
            $where .= implode(' ', $v).' ';
        }
        $where = rtrim($where, $tmp);

        $orderBy = '';
        foreach ($this->orderBy as $v) {
            $orderBy .= ', '.implode(' ', $v);
        }
        $orderBy = ltrim($orderBy, ', ');

        $limit = implode(', ',$this->limit);

        $set = '';
        foreach ($this->data as $k=>$v) {
            $set .= ", $k = ?";
        }
        $set = ltrim($set, ',');

        $sql = "DELETE FROM {$this->table}";

        if($where) {
            $sql .= " WHERE {$where}";
        }

        if($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }

        if($limit) {
            $sql .= " LIMIT {$limit}";
        }

        return $sql;
    }
}