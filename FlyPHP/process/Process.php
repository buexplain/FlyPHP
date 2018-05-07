<?php namespace fly\process;

use \swoole_process;

class Process extends swoole_process
{
    /**
     * 检查进程是否在运行
     * @param $pid
     * @return bool
     */
    public static function isRunning($pid)
    {
        if(is_null($pid)) return false;
        return self::kill($pid, 0);
    }

    /**
     * 重启某个进程
     * @param $pid
     */
    public static function reload($pid)
    {
        self::kill($pid, SIGUSR1);
    }

}