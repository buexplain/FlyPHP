<?php namespace fly\database\builder\mysql;

use fly\contracts\database\builder\Insert as AbstractInsert;

/**
 * 插入语句构造器
 * @package fly\database
 */
class Insert extends AbstractInsert
{
    protected $table;
    protected $data = [];

    /**
     * @param $table
     * @return \fly\contracts\database\builder\Insert
     */
    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * @param $array
     * @return \fly\contracts\database\builder\Insert
     */
    public function data(array $array)
    {
        $this->data = array_merge($this->data, $array);
        return $this;
    }

    public function getData()
    {
        $result = [];
        foreach ($this->data as $k=>$v) {
            if(is_array($v)) {
                $result = array_merge($result, array_values($v));
            }else{
                $result = array_values($this->data);
                break;
            }
        }
        return $result;
    }

    public function toSql()
    {
        $values = ['', ''];
        $flag = false;
        foreach($this->data as $k=>$v) {
            if(is_array($v)) {
                foreach ($v as $k2=>$v2) {
                    $values[0] .= ", $k2";
                    $values[1] .= ", ?";
                }
                $flag = true;
                break;
            }else{
                $values[0] .= ", $k";
                $values[1] .= ", ?";
            }
        }

        $values[0] = ltrim($values[0], ', ');
        $values[1] = ltrim($values[1], ', ');

        if($flag) {
            $values[1] = implode('), (', array_fill(0, count($this->data), $values[1]));
            $values = '('.implode(')  VALUES  (', $values).')';
        }else{
            $values = '('.implode(')  VALUE  (', $values).')';
        }

        $sql = "INSERT INTO {$this->table} {$values}";

        return $sql;
    }
}