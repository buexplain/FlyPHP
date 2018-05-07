<?php namespace fly\contracts\database;

/**
 * 主从代理
 * @package fly\contracts\database
 */
interface DB extends FlyPDO
{
    /**
     * 返回某个主库 FlyPDO
     * @param null|mixed $key
     * @return \fly\database\FlyPDO
     */
    public function master($key=null);
    /**
     * 返回某个从库 FlyPDO
     * @param null|mixed $key
     * @return \fly\database\FlyPDO
     */
    public function slave($key=null);
}