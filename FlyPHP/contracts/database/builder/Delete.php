<?php namespace fly\contracts\database\builder;

/**
 * delete sql 构造器
 * @package fly\contracts\database\builder
 */
abstract class Delete extends Builder
{
    /**
     * @param $table
     * @return Delete
     */
    abstract public function table($table);

    /**
     * @param $column
     * @param array ...$param
     * @return Delete
     */
    abstract public function where($column, ...$param);

    /**
     * @param $column
     * @param array $value
     * @param string $boolean
     * @return Delete
     */
    abstract public function whereBetween($column, array $value, $boolean='AND');

    /**
     * @param $column
     * @param array $value
     * @param string $boolean
     * @return Delete
     */
    abstract public function whereNotBetween($column, array $value, $boolean='AND');

    /**
     * @param $column
     * @param array $value
     * @param string $boolean
     * @return Delete
     */
    abstract public function whereIn($column, array $value, $boolean='AND');

    /**
     * @param $column
     * @param array $value
     * @param string $boolean
     * @return Delete
     */
    abstract public function whereNotIn($column, array $value, $boolean='AND');

    /**
     * @param $column
     * @param string $boolean
     * @return Delete
     */
    abstract public function whereNull($column, $boolean='AND');

    /**
     * @param $column
     * @param string $boolean
     * @return Delete
     */
    abstract public function whereNotNull($column, $boolean='AND');

    /**
     * @param $column
     * @param string $direction
     * @return Delete
     */
    abstract public function orderBy($column, $direction='ASC');

    /**
     * @param $offset
     * @param $number
     * @return Delete
     */
    abstract public function limit($offset, $number);
}