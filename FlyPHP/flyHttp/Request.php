<?php namespace fly\flyHttp;

use fly\contracts\http\Request as InterfaceRequest;
use fly\http\UploadFile;

class Request implements InterfaceRequest
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * 返回当前的请求类型
     * @return string
     */
    public function method()
    {
        return strtoupper($this->request->server['request_method']);
    }

    /**
     * 返回当前的请求路径
     * @return string
     */
    public function path()
    {
        return trim($this->request->server['path_info'], '/');
    }

    /**
     * 判断当前请求是否是ajax请求
     * @return bool
     */
    public function is_ajax()
    {
        return 'xmlhttprequest' == (isset($this->request->header['x-requested-with']) ? strtolower($this->request->header['x-requested-with']) : null);
    }

    /**
     * 获取一个http请求的数据
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function get($key, $default=null)
    {
        return isset($this->request->get[$key]) ? $this->request->get[$key] : (isset($this->request->post[$key]) ? $this->request->post[$key] : $default);
    }

    /**
     * 获取一个cookie的值
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public function cookie($key, $default=null)
    {
        return isset($this->request->cookie[$key]) ? $this->request->cookie[$key] : $default;
    }

    /**
     * 返回上传的文件对象
     * @param $key
     * @return array|UploadFile|null
     */
    public function file($key)
    {
        if(!isset($this->request->files[$key])) {
            return null;
        }
        $file = $this->request->files[$key];
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