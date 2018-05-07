<?php namespace fly\container;

use fly\contracts\container\Container as InterfaceContainer;
use ReflectionClass;
use ReflectionMethod;
use ReflectionFunction;
use Exception;
use Closure;

/**
 * 容器类
 * @package fly\container
 */
class Container implements InterfaceContainer
{
    /**
     * 已经实例化的对象
     * @var array
     */
    private $instanceArr        = [];

    /**
     * 等待实例化的类
     * @var array
     */
    private $pendingInstanceArr = [];

    /**
     * 是否单例模式
     * @var array
     */
    private $shareArr           = [];

    /**
     * 实例化对象时的相关情景
     * @var array
     */
    private $contextArr         = [];

    /**
     * 删除绑定的实例
     * @param string $abstract
     */
    final public function del($abstract)
    {
        unset($this->instanceArr[$abstract]);
    }

    /**
     * 判断实例是否存在
     * @param string $abstract
     * @return bool
     */
    final public function has($abstract)
    {
        return isset($this->instanceArr[$abstract]);
    }

    /**
     * 绑定到容器
     * @param string $abstract
     * @param null|\Closure|object|string $concrete
     * @param bool $share 是否为单例模式
     * @return $this
     */
    final public function set($abstract, $concrete=null, $share=true)
    {
        if(is_object($concrete) && !($concrete instanceof Closure)) {
            $this->instanceArr[$abstract] = $concrete;
        }else{
            if(is_null($concrete)) {
                $concrete = $abstract;
            }
            $this->pendingInstanceArr[$abstract] = $concrete;
        }
        $this->shareArr[$abstract] = $share;
        return $this;
    }

    /**
     * 通过容器得到一个对象
     * @param string $abstract
     * @param array $paramArr 对象初始化的时候需要的参数
     * @return mixed|object
     */
    final public function get($abstract, array $paramArr=[])
    {
        if(isset($this->instanceArr[$abstract])) {
            if($this->shareArr[$abstract] === true) {
                return $this->instanceArr[$abstract];
            }else{
                $result = $this->instanceArr[$abstract];
                unset($this->instanceArr[$abstract]);
                return $result;
            }
        }else{
            if(!isset($this->pendingInstanceArr[$abstract])) {
                $this->set($abstract, null, false);
            }
            $result = $this->make($abstract, $paramArr);
            if($this->shareArr[$abstract] === true) {
                $this->instanceArr[$abstract] = $result;
            }
            return $result;
        }
    }

    /**
     * 实例化一个对象
     * @param string $abstract
     * @param array $paramArr
     * @return mixed|object
     * @throws Exception
     */
    private function make($abstract, $paramArr)
    {
        $pendingInstance = $this->pendingInstanceArr[$abstract];
        if($pendingInstance instanceof Closure) {
            return call_user_func_array($pendingInstance, [$this, $paramArr]);
        }
        $reflection = new ReflectionClass($pendingInstance);
        if($reflection->isInstantiable() === false) {
            throw new Exception("Can't instantiate class {$pendingInstance}");
        }
        $paramArr = $this->getConstructParam($reflection, $paramArr);
        if(count($paramArr) == 0) {
            return new $pendingInstance;
        }
        return $reflection->newInstanceArgs($paramArr);
    }

    /**
     * 情景绑定
     * @param string $when
     * @param string $need
     * @param string $give
     */
    final public function setContext($when, $need, $give)
    {
        if(!isset($this->contextArr[$when])) {
            $this->contextArr[$when] = [];
        }
        $this->contextArr[$when][$need] = $give;
    }

    /**
     * 获取情景
     * @param string $when
     * @param string $need
     * @return string
     */
    private function getContext($when, $need)
    {
        if(isset($this->contextArr[$when][$need])) {
            return $this->contextArr[$when][$need];
        }
        return $need;
    }

    /**
     * 获取构造函数的参数
     * @param $reflection
     * @param array $paramArr
     * @return array
     * @throws Exception
     */
    private function getConstructParam(ReflectionClass $reflection, array $paramArr=[])
    {
        $constructor = $reflection->getConstructor();
        $abstract    = $reflection->getName();
        if(is_null($constructor)) return [];
        $result = [];
        $classParamArr = $constructor->getParameters();
        foreach($classParamArr as $value) {
            if(isset($paramArr[$value->name])) {
                $result[] = $paramArr[$value->name];
            }elseif($value->isDefaultValueAvailable()) {
                $result[] = $value->getDefaultValue();
            }else{
                $tmp = $value->getClass();
                if(is_null($tmp)) {
                    throw new Exception("This Class {$reflection->getName()} parameters {$value->name} can not be getClass");
                }
                $result[] = $this->get($this->getContext($abstract, $tmp->getName()));
            }
        }
        return $result;
    }

    /**
     * 获取一个方法的参数
     * @param string $className
     * @param string $methodName
     * @param array $paramArr
     * @return array
     * @throws Exception
     */
    final public function getMethodParam($className, $methodName, array $paramArr=[])
    {
        $reflectionMethod = new ReflectionMethod($className, $methodName);
        $result = [];
        $methodParamArr = $reflectionMethod->getParameters();
        foreach($methodParamArr as $value) {
            if(isset($paramArr[$value->name])) {
                $result[] = $paramArr[$value->name];
            }elseif($value->isDefaultValueAvailable()){
                $result[] = $value->getDefaultValue();
            }else{
                $tmp = $value->getClass();
                if(is_null($tmp)) {
                    throw new Exception("This Class {$reflectionMethod->getName()} parameters {$value->name} can not be getClass");
                }
                $result[] = $this->get($tmp->getName());
            }
        }
        return $result;
    }

    /**
     * 获取一个函数的参数
     * @param string $functionName
     * @param array $paramArr
     * @return array
     * @throws Exception
     */
    final public function getFunctionParam($functionName, array $paramArr=[])
    {
        $reflectionFunc = new ReflectionFunction($functionName);
        $result = [];
        $functionParamArr = $reflectionFunc->getParameters();
        foreach($functionParamArr as $value) {
            if(isset($paramArr[$value->name])) {
                $result[] = $paramArr[$value->name];
            }elseif($value->isDefaultValueAvailable()){
                $result[] = $value->getDefaultValue();
            }else{
                $tmp = $value->getClass();
                if(is_null($tmp)) {
                    throw new Exception("This Function {$reflectionFunc->getName()} parameters {$value->name} can not be getClass");
                }
                $result[] = $this->get($tmp->getName());
            }
        }
        return $result;
    }
}
