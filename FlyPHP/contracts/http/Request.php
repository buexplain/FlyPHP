<?php namespace fly\contracts\http;

interface Request
{
    /**
     * 返回当前的请求类型
     * @return string
     */
    public function method();

    /**
     * 返回当前的请求路径
     * @return string
     */
    public function path();

    /**
     * 判断当前请求是否是ajax请求
     * @return bool
     */
    public function is_ajax();

    /**
     * 获取一个get或者是post的值
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function get($key, $default=null);

    /**
     * 获取一个cookie的值
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public function cookie($key, $default=null);

    /**
     * 返回上传的文件对象
     * @param $key
     * @return array|UploadFile|null
     */
    public function file($key);
}