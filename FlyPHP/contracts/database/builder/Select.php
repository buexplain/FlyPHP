<?php namespace fly\contracts\database\builder;

/**
 * select sql 构造器
 * @package fly\contracts\database\builder
 */
abstract class Select extends Builder
{
    /**
     * @param $table
     * @return Select
     */
    abstract public function table($table);

    /**
     * @param array ...$column
     * @return Select
     */
    abstract public function column(...$column);

    /**
     * @param $table
     * @param $on
     * @param $type
     * @return Select
     */
    abstract public function join($table, $on, $type);

    /**
     * @param $table
     * @param $on
     * @return Select
     */
    abstract public function leftJoin($table, $on);

    /**
     * @param $table
     * @param $on
     * @return Select
     */
    abstract public function rightJoin($table, $on);

    /**
     * @param $table
     * @param $on
     * @return Select
     */
    abstract public function innerJoin($table, $on);

    /**
     * @param $column
     * @param array ...$param
     * @return Select
     */
    abstract public function where($column, ...$param);

    /**
     * @param $column
     * @param array $value
     * @param string $boolean
     * @return Select
     */
    abstract public function whereBetween($column, array $value, $boolean='AND');

    /**
     * @param $column
     * @param array $value
     * @param string $boolean
     * @return Select
     */
    abstract public function whereNotBetween($column, array $value, $boolean='AND');

    /**
     * @param $column
     * @param array $value
     * @param string $boolean
     * @return Select
     */
    abstract public function whereIn($column, array $value, $boolean='AND');

    /**
     * @param $column
     * @param array $value
     * @param string $boolean
     * @return Select
     */
    abstract public function whereNotIn($column, array $value, $boolean='AND');

    /**
     * @param $column
     * @param string $boolean
     * @return Select
     */
    abstract public function whereNull($column, $boolean='AND');

    /**
     * @param $column
     * @param string $boolean
     * @return Select
     */
    abstract public function whereNotNull($column, $boolean='AND');

    /**
     * @param $column
     * @return Select
     */
    abstract public function groupBy($column);

    /**
     * @param $column
     * @param $operator
     * @param $value
     * @param string $boolean
     * @return Select
     */
    abstract public function having($column, $operator, $value, $boolean='AND');

    /**
     * @param $column
     * @param string $direction
     * @return Select
     */
    abstract public function orderBy($column, $direction='ASC');

    /**
     * @param $offset
     * @param $number
     * @return Select
     */
    abstract public function limit($offset, $number);

    /**
     * @param Select $selectBuilder
     * @param string $type
     * @return Select
     */
    abstract public function union(Select $selectBuilder, $type='');
}