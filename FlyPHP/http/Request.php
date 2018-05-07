<?php namespace fly\http;

use fly\contracts\http\Request as InterfaceRequest;

class Request implements InterfaceRequest
{
    /**
     * 返回当前的请求类型
     * @return string
     */
    public function method()
    {
        return isset($_SERVER['REQUEST_METHOD']) ? strtoupper($_SERVER['REQUEST_METHOD']) : null;
    }

    /**
     * 返回当前的请求路径
     * @return string
     */
    public function path()
    {
        return trim(isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : (isset($_SERVER['REDIRECT_URL']) ? $_SERVER['REDIRECT_URL'] : ''), '/');
    }

    /**
     * 判断当前请求是否是ajax请求
     * @return bool
     */
    public function is_ajax()
    {
        return 'xmlhttprequest' == (isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) : null);
    }

    /**
     * 获取一个get或者是post的值
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function get($key, $default=null)
    {
        return isset($_GET[$key]) ? $_GET[$key] : (isset($_POST[$key]) ? $_POST[$key] : $default);
    }

    /**
     * 获取一个cookie的值
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public function cookie($key, $default=null)
    {
        return isset($_COOKIE[$key]) ? $_COOKIE[$key] : $default;
    }

    /**
     * 返回上传的文件对象
     * @param $key
     * @return array|UploadFile|null
     */
    public function file($key)
    {
        if(!isset($_FILES[$key])) {
            return null;
        }
        $file = $_FILES[$key];
        if(isset($file['name'])) {
            return new UploadFile($file);
        }else{
            $tmp = [];
            foreach ($file as $v) {
                $tmp[] = new UploadFile($v);
            }
            return $tmp;
        }
    }
}