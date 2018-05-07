<?php namespace fly\console;

use fly\contracts\console\Request as InterfaceRequest;

/**
 * 控制台请求类
 * @package fly\console
 */
class Request implements InterfaceRequest
{
    /**
     * 控制台输入的参数
     * @var array
     */
    protected $params = [];

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->parseParam();
    }

    /**
     * 得到控制台输入的某个参数的值
     * @param string $index
     * @param null $default
     * @return mixed|null
     */
    public function get($index, $default=null)
    {
        return isset($this->params[$index]) ? $this->params[$index] : $default;
    }

    /**
     * 返回所有的控制台输入
     * @return array
     */
    public function all()
    {
        return $this->params;
    }

    /**
     * 解析控制台参数
     */
    protected function parseParam()
    {
        global $argv;
        reset($argv);
        $this->params['scriptName'] = rtrim(basename(current($argv)), '.php');
        next($argv);
        if(current($argv) !== false) {
            $this->params['commandName'] = current($argv);
        }
        next($argv);
        while(($name = current($argv)) !== false) {
            next($argv);
            if($name[0] == '-') {
                $value = current($argv);
                if($value === false || $value[0] == '-') {
                    $this->params[$name] = true;
                }else{
                    switch ($value) {
                        case 'true':
                            $value = true;
                            break;
                        case 'false':
                            $value = false;
                            break;
                        case 'null':
                            $value = null;
                            break;
                    }
                    $this->params[$name] = $value;
                    next($argv);
                }
            }else{
                $this->params[$name] = true;
            }
        }
        reset($argv);
    }
}