<?php namespace fly\database\builder\mysql;

use InvalidArgumentException;

trait Where
{
    protected $where = [];
    protected $whereValue = [];

    public function where($column, ...$param)
    {
        $counter = count($param);
        switch ($counter) {
            case 1:
                $operator = '=';
                $value = $param[0];
                $boolean = 'AND';
                break;
            case 2:
                $operator = $param[0];
                $value = $param[1];
                $boolean = 'AND';
                break;
            case 3:
                $operator = $param[0];
                $value = $param[1];
                $boolean = $param[2];
                break;
            default:
                throw new InvalidArgumentException('where parameter error');
        }

        $placeholder = "?";
        $this->whereValue[] = $value;

        $this->where[] = [
            'column'=>$column,
            'operator'=>strtoupper($operator),
            'value'=>$placeholder,
            'boolean'=>strtoupper($boolean)
        ];
        return $this;
    }

    public function whereBetween($column, array $value, $boolean='AND')
    {
        if(count($value) != 2) {
            throw new InvalidArgumentException('whereBetween parameter error');
        }

        $this->where[] = [
            'column'=>$column,
            'operator'=>'BETWEEN ? AND ?',
            'value'=>'',
            'boolean'=>strtoupper($boolean)
        ];
        $this->whereValue = array_merge($this->whereValue, $value);
        return $this;
    }

    public function whereNotBetween($column, array $value, $boolean='AND')
    {
        if(count($value) != 2) {
            throw new InvalidArgumentException('whereNotBetween parameter error');
        }

        $this->where[] = [
            'column'=>$column,
            'operator'=>'NOT BETWEEN ? AND ?',
            'value'=>'',
            'boolean'=>strtoupper($boolean)
        ];
        $this->whereValue = array_merge($this->whereValue, $value);
        return $this;
    }

    public function whereIn($column, array $value, $boolean='AND')
    {
        $this->whereValue = array_merge($this->whereValue, $value);
        $placeholder = '('.implode(', ', array_fill(0, count($value) , '?')).')';

        $this->where[] = [
            'column'=>$column,
            'operator'=>'IN',
            'value'=>$placeholder,
            'boolean'=>strtoupper($boolean)
        ];
        return $this;
    }

    public function whereNotIn($column, array $value, $boolean='AND')
    {
        $this->whereValue = array_merge($this->whereValue, $value);
        $placeholder = '('.implode(', ', array_fill(0, count($value) , '?')).')';

        $this->where[] = [
            'column'=>$column,
            'operator'=>'NOT IN',
            'value'=>$placeholder,
            'boolean'=>strtoupper($boolean)
        ];
        return $this;
    }

    public function whereNull($column, $boolean='AND')
    {
        $this->where[] = [
            'column'=>'',
            'operator'=>"!IFNULL($column, FALSE)",
            'value'=>'',
            'boolean'=>strtoupper($boolean)
        ];
        return $this;
    }

    public function whereNotNull($column, $boolean='AND')
    {
        $this->where[] = [
            'column'=>'',
            'operator'=>"IFNULL($column, FALSE)",
            'value'=>'',
            'boolean'=>strtoupper($boolean)
        ];
        return $this;
    }
}