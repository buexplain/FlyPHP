<?php namespace fly\console;

use fly\contracts\console\Response as AbstractResponse;

/**
 * 控制台响应类
 * @package fly\console
 */
class Response extends AbstractResponse
{
    /**
     * 响应正常信息
     * @param mixed $msg
     * @return $this
     */
    public function info($msg)
    {
        $this->obStart($msg);
        print_r($msg);
        echo PHP_EOL;
        $this->obEnd();
        return $this;
    }

    /**
     * 响应错误信息
     * @param mixed $msg
     * @return $this
     */
    public function error($msg)
    {
        $this->obStart($msg);
        $colorStart = "\e[31m";;
        $colorEnd = "\e[0m";
        echo $colorStart;
        print_r($msg);
        echo PHP_EOL;
        echo $colorEnd;
        $this->obEnd();
        return $this;
    }
}