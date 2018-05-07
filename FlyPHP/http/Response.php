<?php namespace fly\http;

use fly\contracts\http\Response as InterfaceResponse;
use fly\contracts\http\View as CompilerMain;

class Response implements InterfaceResponse
{
    /**
     * 视图名称
     * @var
     */
    protected $view;

    /**
     * 需要响应的数据
     * @var array
     */
    protected $data = [];

    /**
     * http code
     * @var int
     */
    protected $statusCode = 200;

    /**
     * http header
     * @var array
     */
    protected $header = [];

    /**
     * http cookie
     * @var array
     */
    protected $cookie = [];

    /**
     * 服务容器
     * @var App
     */
    protected $app;

    /**
     * 当前响应格式
     * @var int
     */
    protected $format;

    /**
     * jsonp 响应的回调函数名称
     * @var string
     */
    protected $jsonpCallback;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * 存储需要响应的内容
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function with($key, $value)
    {
        $this->data[$key] = $value;
        return $this;
    }

    /**
     * 发送响应数据
     */
    public function send()
    {
        http_response_code($this->statusCode);
        foreach($this->header as $key=>$value) {
            header("$key: {$value}");
        }
        foreach ($this->cookie as $value) {
            setcookie($value['name'], $value['value'], $value['expire'], $value['path'], $value['domain'], $value['secure'], $value['httpOnly']);
        }
        echo $this->format();
    }

    /**
     * 格式化响应内容
     * @return string
     */
    protected function format()
    {
        $result = '';
        switch($this->format) {
            case self::FORMAT_HTML:
                if(!empty($this->view)) {
                    $compilerMain = $this->app->get(CompilerMain::class);
                    $result = $compilerMain->render($this->view, $this->data);
                    $compilerMain->flush();
                }
                break;
            case self::FORMAT_JSON:
                $result = json_encode($this->data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
                break;
            case self::FORMAT_JSONP:
                $result = $this->jsonpCallback.'('.json_encode($this->data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES).')';
                break;
        }
        return $result;
    }

    /**
     * 设置 http status code
     * @param int $code
     * @return $this
     */
    public function setStatusCode($code)
    {
        $this->statusCode = $code;
        return $this;
    }

    /**
     * 设置http响应头
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function setHeader($key, $value)
    {
        $this->header[$key] = $value;
        return $this;
    }

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
    public function setCookie($name, $value, $expire, $path='', $domain='', $secure=false, $httpOnly=false)
    {
        $this->cookie[] = [
            'name'=>$name,
            '$value'=>$value,
            'value'=>$value,
            'expire'=>$expire+time(),
            'path'=>$path,
            'domain'=>$domain,
            'secure'=>$secure,
            'httpOnly'=>$httpOnly,
        ];
        return $this;
    }

    /**
     * 返回当前的响应格式
     * @return int
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * 设置视图响应
     * @param string $view
     * @return \fly\contracts\http\Response
     */
    public function view($view)
    {
        $this->setHeader('Content-Type', 'text/html; charset=UTF-8');
        $this->format = self::FORMAT_HTML;
        $this->view = $view;
        return $this;
    }

    /**
     * 设置json响应
     * @param array $data
     * @return \fly\contracts\http\Response
     */
    public function json(array $data)
    {
        foreach ($data as $k=>$v) {
            $this->with($k, $v);
        }
        $this->setHeader('Content-Type', 'application/json; charset=UTF-8');
        $this->format = self::FORMAT_JSON;
        return $this;
    }

    /**
     * 设置jsonp响应
     * @param array $data
     * @param string $callback
     * @return \fly\contracts\http\Response
     */
    public function jsonp(array $data, $callback='callback')
    {
        foreach ($data as $k=>$v) {
            $this->with($k, $v);
        }
        $this->jsonpCallback = $callback;
        $this->setHeader('Content-Type', 'text/plain; charset=UTF-8');
        $this->format = self::FORMAT_JSONP;
        return $this;
    }
}