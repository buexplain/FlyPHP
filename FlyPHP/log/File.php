<?php namespace fly\log;

use fly\contracts\log\Log;

/**
 * 文件日志驱动
 * @package fly\log
 */
class File implements Log
{
    protected $config = [
        'level'=>self::LEVEL_DEBUG,
    ];

    protected $path;

    public function __construct(array $config, $path)
    {
        $this->config = array_merge($this->config, $config);
        $this->path = $path;
    }

    protected function save($level, $message, $tag)
    {
        $file = $this->path.DIRECTORY_SEPARATOR.'fly-'.date('Y-m-d').'.log';
        $str = '['.date('Y-m-d H:i:s').'] '.$level.' ['.$tag.'] '.$this->toString($message).PHP_EOL;
        file_put_contents($file, $str, FILE_APPEND|LOCK_EX);
    }

    protected function toString($data)
    {
        if(is_array($data)) {
            return json_encode($data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        }else if(is_object($data)) {
            return serialize($data);
        }else{
            return $data;
        }
    }

    public function error($message, $tag=null)
    {
        if($this->config['level'] < self::LEVEL_ERROR) {
            return;
        }

        $this->save('[  error  ]', $message, $tag);
    }

    public function warning($message, $tag=null)
    {
        if($this->config['level'] < self::LEVEL_WARNING) {
            return;
        }
        $this->save('[ warning ]', $message, $tag);
    }

    public function info($message, $tag=null)
    {
        if($this->config['level'] < self::LEVEL_INFO) {
            return;
        }
        $this->save('[  info   ]', $message, $tag);
    }

    public function debug($message, $tag=null)
    {
        if($this->config['level'] < self::LEVEL_DEBUG) {
            return;
        }
        $this->save('[  debug  ]', $message, $tag);
    }
}