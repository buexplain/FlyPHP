<?php namespace fly\database\builder\mysql;

use fly\contracts\database\builder\Select as AbstractSelect;

/**
 * 查询语句构造器
 * @package fly\database
 */
class Select extends AbstractSelect
{
    use Where;

    protected $table;
    protected $column=['*'];
    protected $join = [];
    protected $groupBy = [];
    protected $having = [];
    protected $havingValue = [];
    protected $orderBy = [];
    protected $limit = [];
    protected $union = [];

    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    public function column(...$column)
    {
        $this->column = isset($column[0]) && is_array($column[0]) ? $column[0] : $column;
        return $this;
    }

    public function join($table, $on, $type)
    {
        $this->join[] = [
            'type'=>$type,
            'table'=>$table,
            'on'=>$on,
        ];
        return $this;
    }

    public function leftJoin($table, $on)
    {
        $this->join($table, $on, 'LEFT');
        return $this;
    }

    public function rightJoin($table, $on)
    {
        $this->join($table, $on, 'RIGHT');
        return $this;
    }

    public function innerJoin($table, $on)
    {
        $this->join($table, $on, 'INNER');
        return $this;
    }

    public function groupBy($column)
    {
        $this->groupBy[] = $column;
        return $this;
    }

    public function having($column, $operator, $value, $boolean='AND')
    {
        if(is_array($value)) {
            $this->havingValue = array_merge($this->havingValue, $value);
            $placeholder = '('.implode(', ', array_fill(0, count($value) , '?')).')';
        }else{
            $placeholder = "?";
            $this->havingValue[] = $value;
        }
        $this->having[] = [
            'column'=>$column,
            'operator'=>strtoupper($operator),
            'value'=>$placeholder,
            'boolean'=>strtoupper($boolean)
        ];
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

    public function union(AbstractSelect $selectBuilder, $type='')
    {
        $this->union[] = [
            'selectBuilder'=>$selectBuilder,
            'type'=>$type ? 'ALL' : ''
        ];
        return $this;
    }

    public function getData()
    {
        $result = array_merge($this->whereValue, $this->havingValue);
        foreach ($this->union as $v) {
            $result = array_merge($result, $v['selectBuilder']->getData());
        }
        return $result;
    }

    public function toSql()
    {
        $column = count($this->column) == 1 ? implode('', $this->column) : implode(',', $this->column);

        $join = '';
        foreach ($this->join as $v) {
            $join .= ' '.$v['type'].' JOIN '.$v['table'].' ON '.$v['on'];
        }

        $where = '';
        $tmp = '';
        foreach ($this->where as $v) {
            $tmp = $v['boolean'].' ';
            $where .= implode(' ', $v).' ';
        }
        $where = rtrim($where, $tmp);

        $groupBy = implode(', ', $this->groupBy);

        $having = '';
        $tmp = '';
        foreach ($this->having as $v) {
            $tmp = $v['boolean'].' ';
            $having .= implode(' ', $v).' ';
        }
        $having = rtrim($having, $tmp);

        $orderBy = '';
        foreach ($this->orderBy as $v) {
            $orderBy .= ', '.implode(' ', $v);
        }
        $orderBy = ltrim($orderBy, ', ');

        $limit = implode(', ',$this->limit);

        $sql = 'SELECT '.$column.' FROM '.$this->table;

        if($join) {
            $sql .= $join;
        }

        if($where) {
            $sql .= ' WHERE '.$where;
        }

        if($groupBy) {
            $sql .= ' GROUP BY '.$groupBy;
        }

        if($having) {
            $sql .= ' HAVING '.$having;
        }

        if($orderBy) {
            $sql .= ' ORDER BY '.$orderBy;
        }

        if($limit) {
            $sql .= ' LIMIT '.$limit;
        }

        foreach ($this->union as $v) {
            $sql .= ' UNION '.$v['type']. ' '.$v['selectBuilder']->toSql();
        }

        return $sql;
    }
}