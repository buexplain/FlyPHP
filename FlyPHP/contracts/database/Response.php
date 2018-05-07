<?php namespace fly\contracts\database;

use PDOStatement;

interface Response
{
    public function __construct(PDOStatement $sth, $lastInsertId);
    public function getInsertId();
    public function getRowCount();
    public function first();
    public function get();
    public function pluck($key);
    public function lists($key, $value=null);
    public function groupBy($key);
    public function keyBy($key);
    public function sortBy($key, $direction=SORT_ASC, $type=SORT_NUMERIC);
}