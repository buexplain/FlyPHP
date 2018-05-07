<?php namespace fly\contracts\http;

abstract class Session
{
    /**
     * session配置
     * @var array
     */
    protected $config = [
        'driver'=>'file',
        'name'=>'flySid',
        'expire'=>3600,
        'path'=>'/',
        'domain'=>'',
        'secure'=>false,
        'httpOnly'=>true,
    ];

    /**
     * 当前请求对象
     * @var Request
     */
    protected $request;

    /**
     * 当前的响应对象
     * @var Response
     */
    protected $response;

    /**
     * 当前session id
     * @var
     */
    protected $sid;

    /**
     * 当前的session数据
     * @var array
     */
    protected $data = [];

    public function __construct(array $config, Request $request,Response $response)
    {
        $this->config = array_merge($this->config, $config);
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * 返回当前session id
     * @return string
     */
    public function getId()
    {
        return $this->sid;
    }

    /**
     * 返回session的所有的值
     * @return array
     */
    public function all()
    {
        return $this->data;
    }

    /**
     * 从session中返回某个值
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public function get($key, $default=null)
    {
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }

    /**
     * 从session中返回一个值并删除它
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public function pull($key, $default=null)
    {
        $result = isset($this->data[$key]) ? $this->data[$key] : $default;
        unset($this->data[$key]);
        return $result;
    }

    /**
     * 把某个值写入到session
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * 删除session中的某个值
     * @param $key
     */
    public function del($key)
    {
        unset($this->data[$key]);
    }

    /**
     * 创建一个session id
     * @return string
     */
    protected function createSid()
    {
        if(function_exists('session_create_id')) {
            return session_create_id();
        }
        $ip  = gethostbyname(php_uname('n'));
        $pid = getmypid();
        $tid = '';
        $mec = ceil(microtime(true) * 1000);
        $rand = mt_rand(1, 9999);
        return substr(md5($ip.$pid.$tid.$mec.$rand), 8, 16);
    }

    /**
     * 重置一个session id
     */
    abstract public function regenerate();

    /**
     * 清空session
     */
    abstract public function clear();

    /**
     * 保存session到存储介质
     */
    abstract public function save();
}