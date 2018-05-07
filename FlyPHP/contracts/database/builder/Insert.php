<?php namespace fly\contracts\database\builder;

/**
 * insert sql 构造器
 * @package fly\contracts\database\builder
 */
abstract class Insert extends Builder
{
    /**
     * @param $table
     * @return Insert
     */
    abstract public function table($table);

    /**
     * @param $array
     * @return Insert
     */
    abstract public function data(array $array);
}