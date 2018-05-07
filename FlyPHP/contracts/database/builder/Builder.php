<?php namespace fly\contracts\database\builder;

use fly\contracts\database\FlyPDO;

/**
 * sql构造器公共抽象
 * @package fly\contracts\database\builder
 */
abstract class Builder
{
    /**
     * @var FlyPDO
     */
    protected $flyPDO;

    public function __construct(FlyPDO $flyPDO)
    {
        $this->flyPDO = $flyPDO;
    }

    /**
     * 返回sql绑定需要的数据
     * @return array
     */
    abstract public function getData();

    /**
     * 返回一条sql语句
     * @return string
     */
    abstract public function toSql();

    /**
     * 执行当前的语句
     * @return \fly\contracts\database\Response
     */
    public function execute()
    {
        return $this->flyPDO->execute($this->toSql(), $this->getData());
    }
}