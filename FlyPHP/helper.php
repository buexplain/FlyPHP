<?php

if(!function_exists('flyE')) {
    /**
     * 视图引擎防xss函数
     * @param $s
     * @return string
     */
    function flyE($s)
    {
        return htmlspecialchars($s, ENT_QUOTES, 'UTF-8', false);
    }
}

if(!function_exists('dd')) {
    /**
     * 调试打印 给程序员看的
     */
    function dd()
    {
        throw new \fly\http\error\DebugException(func_get_args());
    }
}

if(!function_exists('abort')) {
    /**
     *  中断程序 给用户看的
     * @param $message
     * @param null $format
     */
    function abort($message, $format=null)
    {
        \fly\http\App::getInstance()->abort($message, 400, $format);
    }
}

if(!function_exists('redirect')) {
    /**
     * 跳转到某个url
     * @param $url
     * @param int $httpCode
     * @return $this
     */
    function redirect($url, $httpCode=302)
    {
        return \fly\http\App::getInstance()->get(\fly\contracts\http\Response::class)
            ->view('')
            ->setHeader('Location', $url)
            ->setStatusCode($httpCode);
    }
}