<?php namespace fly\router;

use fly\contracts\router\Route;

/**
 * 静态类型的路由
 * Class Route
 */
class StaticRoute extends Route
{
    protected $uriArr = [];
    protected $method;

    public function __construct($uri, $controller, $action, $method)
    {
        $this->uri        = trim($uri, '/');
        $this->controller = $controller;
        $this->action     = $action;
        $this->method     = strtoupper($method);
        $this->parseUri();
    }

    protected function parseUri()
    {
        //静态uri
        $token = [];
        //动态参数
        $param = [];
        //是否存在可选参数
        $optional = false;
        if(strlen($this->uri) > 0) {
            $uri = explode('/', $this->uri);
            foreach ($uri as $k=>$v) {
                if(isset($v[0]) && $v[0] == ':') {
                    if(substr($v, -1) == '?') {
                        //可选参数
                        $optional = true;
                        $param[] = [substr($v, 1, strlen($v) -2), true];
                    }else{
                        //不可选参数
                        $param[] = [substr($v, 1, strlen($v)), false];
                    }
                    $token[] = '';
                }else{
                    $token[] = $v;
                    $param[] = '';
                }
            }
        }
        $this->uriArr = [
            //静态部分
            'token'=>$token,
            //动态参数部分
            'param'=>$param,
            //路由深度
            'counter'=>count($token),
            //是否存在可选参数
            'optional'=>$optional
        ];
    }

    public function match(array $array, $method)
    {
        $this->params = [];

        if($this->method != 'ANY' && $this->method != $method) {
            return false;
        }

        if(count($array) > $this->uriArr['counter'] || ($this->uriArr['optional'] == false && count($array) != $this->uriArr['counter'])) {
            return false;
        }

        if($this->uriArr['optional'] && count($array) > $this->uriArr['counter']) {
            return false;
        }

        foreach ($this->uriArr['token'] as $k=>$v) {
            if($v != '' && (!isset($array[$k]) || $array[$k] != $v)) {
                return false;
            }
        }

        foreach ($this->uriArr['param'] as $k=>$v) {
            if($v == '') {
                continue;
            }
            //必选参数
            if($this->uriArr['param'][$k][1] == false) {
                if(!isset($array[$k]) || strlen($array[$k]) == 0) {
                    return false;
                }else{
                    $this->params[$this->uriArr['param'][$k][0]] = $array[$k];
                }
            }elseif(isset($array[$k]) && strlen($array[$k]) > 0) {
                $this->params[$this->uriArr['param'][$k][0]] = $array[$k];
            }
        }

        return true;

    }
}