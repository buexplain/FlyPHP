<?php namespace fly\config;

use fly\contracts\config\Config as InterfaceConfig;
use Exception;

class Config implements InterfaceConfig
{
    protected $config = [];
    protected $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    protected function load($name)
    {
        $file = $this->path.DIRECTORY_SEPARATOR.$name.'.php';

        if(!is_file($file)) {
            throw new Exception("Unable to find config file : {$file}");
        }

        $this->config[$name] = require($file);
    }

    /**
     * 读取配置文件
     * @param $name
     * @return array|mixed|null
     */
    public function get($name)
    {
        $name = explode('.', $name);
        if(!isset($this->config[$name[0]])) {
            $this->load($name[0]);
        }
        $config = $this->config;
        foreach($name as $value) {
            if(isset($config[$value])) {
                $config = $config[$value];
            }else{
                return null;
            }
        }
        return $config;
    }
}