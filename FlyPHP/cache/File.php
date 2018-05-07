<?php namespace fly\cache;

use fly\contracts\cache\Cache as InterfaceCache;
use Exception;

class File implements InterfaceCache
{
    protected $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * 返回缓存目录
     * @param $key
     * @return string
     */
    protected function getPath($key)
    {
        $tmp = md5($key);
        $path = $this->path.DIRECTORY_SEPARATOR.$tmp[0].DIRECTORY_SEPARATOR.$tmp[1].DIRECTORY_SEPARATOR;
        $this->mkFolder($path);
        return $path.$tmp;
    }

    /**
     * 递归创建文件夹
     * @param $path
     * @param int $mode
     * @param bool $recursive
     * @return bool
     */
    protected function mkFolder($path, $mode=0777, $recursive=true) {
        if (!is_dir($path)) {
            if (!$this->mkFolder(dirname($path), $mode, $recursive)) {
                return false;
            }
            if (!mkdir($path, $mode, $recursive)) {
                return false;
            }else{
                chmod($path, $mode);
            }
        }
        return true;
    }

    /**
     * 写入一个值
     * @param $key
     * @param array|integer|string $value
     * @param int $sec
     * @return bool
     */
    public function set($key, $value, $sec=3600)
    {
        try{
            $data = serialize(['data'=>$value, 'timeout'=>$sec+time()]);
            file_put_contents($this->getPath($key), $data, LOCK_EX);
            return true;
        }catch (Exception $e) {
            return false;
        }
    }

    /**
     * 读取一个值
     * @param $key
     * @param null $default
     * @return array|integer|string
     */
    public function get($key, $default=null)
    {
        try{
            $file = $this->getPath($key);
            $data = file_get_contents($file);
            $data = unserialize($data);
            if($data['timeout'] < time()) {
                unlink($file);
                return $default;
            }
            return $data['data'];
        }catch (Exception $e) {
            return $default;
        }
    }

    /**
     * 判断某个key是否存在
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return is_null($this->get($key, null)) ? false : true;
    }

    /**
     * 删除一些key
     * @param $key
     * @return int 删除的key的数量
     */
    public function del($key)
    {
        try{
            unlink($this->getPath($key));
            return 1;
        }catch (Exception $e) {
            return 1;
        }
    }
}