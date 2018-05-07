<?php namespace fly\http\session;

use fly\contracts\http\Session;
use fly\contracts\http\Response;
use fly\contracts\http\Request;
use Exception;

/**
 * session 文件驱动
 * @package fly\session
 */
class File extends Session
{
    /**
     * session文件根目录
     * @var string
     */
    protected $path;

    /**
     * session文件地址
     * @var
     */
    protected $file;

    /**
     * File constructor.
     * @param array $config
     * @param Request $request
     * @param Response $response
     * @param string $path
     */
    public function __construct(array $config, Request $request,Response $response, $path)
    {
        parent::__construct($config, $request, $response);
        $this->path = $path;
        $this->setParam($request->cookie($this->config['name']));
        $this->initData();
    }

    /**
     * 设置参数
     * @param $sid
     */
    protected function setParam($sid)
    {
        $this->sid = is_null($sid) ? $this->createSid() : $sid;
        $this->file = $this->path.DIRECTORY_SEPARATOR.$this->sid[0].DIRECTORY_SEPARATOR.$this->sid[1].DIRECTORY_SEPARATOR;
        $this->mkFolder($this->file);
        $this->file .= $this->sid;
    }

    /**
     * 初始化data数据
     */
    protected function initData()
    {
        if(is_file($this->file)) {
            $tmp = unserialize(file_get_contents($this->file));
            if($tmp['timeout'] > time()) {
                $this->data = $tmp['data'];
            }
        }
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
     * 重置一个session id
     */
    public function regenerate()
    {
        try {
            unlink($this->file);
            $this->setParam($this->createSid());
        }catch (Exception $e) {

        }
    }

    /**
     * 清空session
     */
    public function clear()
    {
        try {
            unlink($this->file);
            $this->response->setCookie(
                $this->config['name'],
                $this->sid,
                0-$this->config['expire'],
                $this->config['path'],
                $this->config['domain'],
                $this->config['secure'],
                $this->config['httpOnly']
            );
            $this->data = null;
        }catch (Exception $e) {

        }
    }

    /**
     * 保存session到磁盘
     */
    public function save()
    {
        if(!is_null($this->data)) {
            $this->response->setCookie(
                $this->config['name'],
                $this->sid,
                $this->config['expire'],
                $this->config['path'],
                $this->config['domain'],
                $this->config['secure'],
                $this->config['httpOnly']
            );
            $data = ['data'=>$this->data, 'timeout'=>time()+$this->config['expire']];
            file_put_contents($this->file, serialize($data), LOCK_EX);
        }
    }
}