<?php namespace fly\contracts\http;


interface Response
{
    const FORMAT_HTML = 0;
    const FORMAT_JSON = 1;
    const FORMAT_JSONP = 2;

    /**
     * 存储需要响应的内容
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function with($key, $value);

    /**
     * 设置 http status code
     * @param int $code
     * @return $this
     */
    public function setStatusCode($code);

    /**
     * 设置http响应头
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function setHeader($key, $value);

    /**
     * 设置cookie
     * @param string $name
     * @param mixed $value
     * @param integer $expire
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httpOnly
     * @return $this
     */
    public function setCookie($name, $value, $expire, $path='', $domain='', $secure=false, $httpOnly=false);

    /**
     * 返回当前的响应格式
     * @return int
     */
    public function getFormat();

    /**
     * 发送响应数据
     */
    public function send();

    /**
     * 设置视图响应
     * @param string $view
     * @return \fly\contracts\http\Response
     */
    public function view($view);

    /**
     * 设置json响应
     * @param array $data
     * @return \fly\contracts\http\Response
     */
    public function json(array $data);

    /**
     * 设置jsonp响应
     * @param array $data
     * @param string $callback
     * @return \fly\contracts\http\Response
     */
    public function jsonp(array $data, $callback='callback');
}