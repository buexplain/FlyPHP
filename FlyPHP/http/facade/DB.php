<?php

use fly\contracts\http\Facade;
use fly\http\App;

/**
 * @method static \fly\contracts\database\builder\Insert insert();
 * @method static \fly\contracts\database\builder\Delete delete();
 * @method static \fly\contracts\database\builder\Update update();
 * @method static \fly\contracts\database\builder\Select select();
 * @method static bool beginTransaction();
 * @method static bool rollBack();
 * @method static bool commit();
 * @method static bool inTransaction();
 * @method static \fly\contracts\database\Response execute($sql, ...$param);
 * @method static \fly\database\FlyPDO master($key=null)
 * @method static \fly\database\FlyPDO slave($key=null)
 */
class DB extends Facade
{
    protected static function getFacadeClass()
    {
        return App::getInstance()->get(\fly\contracts\database\DB::class);
    }
}