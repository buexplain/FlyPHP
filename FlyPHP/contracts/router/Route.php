<?php namespace fly\contracts\router;

abstract class Route
{
    /**
     * uri
     * @var
     */
    protected $uri;

    /**
     * 路由命中后的参数
     * @var array
     */
    protected $params = array();

    /**
     * 控制器
     * @var string
     */
    protected $controller='';

    /**
     * 方法
     * @var string
     */
    protected $action='';

    /**
     * 路由中间件
     * @var array
     */
    protected $middleware = [];

    /**
     * 返回命中后的参数
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * 返回控制器
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * 返回方法
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * 设置控制器
     * @param string $controller
     */
    public function setController($controller)
    {
        $this->controller = $controller;
    }

    /**
     * 设置方法
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * 新增一个中间件
     * @param string $className
     * @return $this
     */
    public function middleware($className)
    {
        $this->middleware[] = $className;
        return $this;
    }

    /**
     * 返回中间件
     * @return array
     */
    public function getMiddleware()
    {
        return $this->middleware;
    }

    /**
     * 匹配路由
     * @param array $array
     * @param $method
     * @return mixed
     */
    abstract public function match(array $array, $method);
}