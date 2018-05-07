<?php namespace fly\flyHttp;

use fly\http\Response as HttpResponse;

use fly\http\App;

class Response extends HttpResponse
{
    protected $response;

    public function __construct(App $app, $response)
    {
        $this->response = $response;
        parent::__construct($app);
    }

    /**
     * 发送响应内容
     */
    public function send()
    {
        $this->response->status($this->statusCode);
        foreach($this->header as $key=>$value) {
            $this->response->header($key, $value);
        }
        foreach ($this->cookie as $value) {
            $this->response->cookie($value['name'], $value['value'], $value['expire'], $value['path'], $value['domain'], $value['secure'], $value['httpOnly']);
        }
        $this->response->end($this->format());
    }
}