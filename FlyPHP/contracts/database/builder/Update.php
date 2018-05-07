<?php namespace fly\contracts\database\builder;

/**
 * update sql 构造器
 * @package fly\contracts\database\builder
 */
abstract class Update extends Builder
{
    /**
     * @param $table
     * @return Update
     */
    abstract public function table($table);

    /**
     * @param $column
     * @param array ...$param
     * @return Update
     */
    abstract public function where($column, ...$param);

    /**
     * @param $column
     * @param array $value
     * @param string $boolean
     * @return Update
     */
    abstract public function whereBetween($column, array $value, $boolean='AND');

    /**
     * @param $column
     * @param array $value
     * @param string $boolean
     * @return Update
     */
    abstract public function whereNotBetween($column, array $value, $boolean='AND');

    /**
     * @param $column
     * @param array $value
     * @param string $boolean
     * @return Update
     */
    abstract public function whereIn($column, array $value, $boolean='AND');

    /**
     * @param $column
     * @param array $value
     * @param string $boolean
     * @return Update
     */
    abstract public function whereNotIn($column, array $value, $boolean='AND');

    /**
     * @param $column
     * @param string $boolean
     * @return Update
     */
    abstract public function whereNull($column, $boolean='AND');

    /**
     * @param $column
     * @param string $boolean
     * @return Update
     */
    abstract public function whereNotNull($column, $boolean='AND');

    /**
     * @param $column
     * @param string $direction
     * @return Update
     */
    abstract public function orderBy($column, $direction='ASC');

    /**
     * @param $offset
     * @param $number
     * @return Update
     */
    abstract public function limit($offset, $number);

    /**
     * @param array $array
     * @return Update
     */
    abstract public function data(array $array);
}