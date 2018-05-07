<?php
namespace fly\contracts\console;

/**
 * 控制台响应抽象类
 * @package fly\contracts\console
 */
abstract class Response
{
    /**
     * 原始的输出信息
     * @var array
     */
    protected $original = [];

    /**
     * 格式化后的输出应信息
     * @var array
     */
    protected $message = [];

    /**
     * 输出缓冲开始
     * @param $msg
     */
    protected function obStart($msg)
    {
        $this->original[] = $msg;
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        ob_start();
    }

    /**
     * 输出缓冲结束
     */
    protected function obEnd()
    {
        $this->message[] = ob_get_clean();
    }

    /**
     * 输出正常信息
     * @param mixed $msg
     * @return $this
     */
    abstract public function info($msg);

    /**
     * 输出错误信息
     * @param mixed $msg
     * @return $this
     */
    abstract public function error($msg);

    /**
     * 输出信息到控制台界面
     */
    public function send()
    {
        foreach($this->message as $msg)
        {
            echo $msg;
        }
        $this->message = [];
        $this->original = [];
    }

    /**
     * 返回原始的输出信息
     * @return array
     */
    public function getMessage()
    {
        return $this->original;
    }
}