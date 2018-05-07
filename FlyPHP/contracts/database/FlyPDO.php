<?php namespace fly\contracts\database;

interface FlyPDO
{
    /**
     * 返回新增语句的构造器
     * @return \fly\contracts\database\builder\Insert
     */
    public function insert();

    /**
     * 返回删除语句的构造器
     * @return \fly\contracts\database\builder\Delete
     */
    public function delete();

    /**
     * 返回更新语句的构造器
     * @return \fly\contracts\database\builder\Update
     */
    public function update();

    /**
     * 返回查询语句的构造器
     * @return \fly\contracts\database\builder\Select
     */
    public function select();

    /**
     * 启动一个事务
     * @return bool
     */
    public function beginTransaction();

    /**
     * 回滚一个事务
     * @return bool
     */
    public function rollBack();

    /**
     * 提交一个事务
     * @return bool
     */
    public function commit();

    /**
     * 检查是否在一个事务内
     * @return bool
     */
    public function inTransaction();

    /**
     * 预编译的方式执行一条sql语句
     * @param string
     * @param array ...$param
     * @return Response
     */
    public function execute($sql, ...$param);
}