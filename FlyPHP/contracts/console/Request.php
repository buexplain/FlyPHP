<?php namespace fly\contracts\console;

/**
 * 控制台请求接口
 * @package fly\console
 */
interface Request
{
    /**
     * 得到控制台输入的某个参数的值
     * @param string $index
     * @param null $default
     * @return mixed|null
     */
    public function get($index, $default=null);

    /**
     * 返回所有的控制台输入
     * @return array
     */
    public function all();
}