<?php namespace fly\contracts\database\builder;

use fly\contracts\database\FlyPDO;

/**
 * sql构造器工厂类
 * @package fly\contracts\database\builder
 */
interface Factory
{
    public function createInsert(FlyPDO $flyPDO);
    public function createDelete(FlyPDO $flyPDO);
    public function createUpdate(FlyPDO $flyPDO);
    public function createSelect(FlyPDO $flyPDO);
}