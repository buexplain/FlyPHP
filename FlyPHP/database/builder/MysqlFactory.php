<?php namespace fly\database\builder;

use fly\contracts\database\builder\Factory;
use fly\contracts\database\FlyPDO;
use fly\database\builder\mysql\Insert;
use fly\database\builder\mysql\Delete;
use fly\database\builder\mysql\Update;
use fly\database\builder\mysql\Select;

/**
 * mysql 数据库的sql构造器的工厂类
 * @package fly\database\builder
 */
class MysqlFactory implements Factory
{
    public function createInsert(FlyPDO $flyPDO)
    {
        return new Insert($flyPDO);
    }

    public function createDelete(FlyPDO $flyPDO)
    {
        return new Delete($flyPDO);
    }

    public function createUpdate(FlyPDO $flyPDO)
    {
        return new Update($flyPDO);
    }

    public function createSelect(FlyPDO $flyPDO)
    {
        return new Select($flyPDO);
    }
}