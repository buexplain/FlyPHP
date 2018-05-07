<?php namespace fly\pipeline;

use fly\contracts\pipeline\Pipeline as InterfacePipeline;
use Closure;
use fly\contracts\container\Container;

class Pipeline implements InterfacePipeline
{
    /**
     * 需要在流水线中处理的对象
     * @var
     */
    private $passable;

    /**
     * 处理者
     * @var
     */
    private $pipes;

    /**
     * 触发的方法
     * @var string
     */
    private $method;

    /**
     * 应用实例
     * @var
     */
    private $app;

    public function __construct(Container $app, $handle)
    {
        $this->app    = $app;
        $this->method = $handle;
    }

    /**
     * 设置需要处理的对象
     * @param $request
     * @return $this
     */
    public function send($data)
    {
        $this->passable = $data;
        return $this;
    }

    /**
     * 需要经过哪些中间件处理
     * @param $pipes
     * @return $this
     */
    public function through(array $pipes)
    {
        $this->pipes = $pipes;
        return $this;
    }

    /**
     * 开始流水线处理
     * @param Closure
     * @return Closure
     */
    public function then(Closure $first)
    {
        return call_user_func(
            array_reduce(array_reverse($this->pipes), $this->getSlice(), $first),
            $this->passable);
    }

    /**
     * 包装迭代对象到闭包
     * @return Closure
     */
    protected function getSlice()
    {
        return function($stack, $pipe) {
            return function($passable) use($stack, $pipe) {
                if($stack instanceof Closure) {
                    return call_user_func([$this->app->get($pipe), $this->method], $passable, $stack);
                }
            };
        };
    }
}