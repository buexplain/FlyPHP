<?php namespace fly\http\error;

use fly\contracts\http\Error as InterfaceError;
use fly\contracts\container\Container;
use fly\contracts\http\Response;
use fly\contracts\log\Log;
use ErrorException;

class Error implements InterfaceError
{
    protected $app;

    protected $view;

    public function __construct(Container $container)
    {
        $this->app = $container;
        $this->view = 'error.';

        error_reporting(E_ALL);
        set_error_handler([$this, 'appError']);
        set_exception_handler([$this, 'appException']);
        register_shutdown_function([$this, 'appShutdown']);
    }

    /**
     * 将错误以异常的形式抛出
     */
    public function appError($type, $message, $file, $line)
    {
        throw new ErrorException($message, 0, $type, $file, $line);
    }

    /**
     * 捕获所有未捕获的异常
     */
    public function appException($e)
    {
        $response = $this->app->get(Response::class);

        $code = 500;
        $view = $this->view.'error';
        $format = Response::FORMAT_HTML;

        if($e instanceof DebugException) {
            $code = $e->getCode();
            $view = $this->view.'debug';
            $format = Response::FORMAT_HTML;
        }elseif($e instanceof AbortException) {
            $code = $e->getCode();
            $view = $this->view.'abort';
            $format = $e->getFormat();
        }else{
            $this->app->get(Log::class)->error($e->getFile().' '.$e->getLine().' : '.$e->getMessage(), 'appException');
        }

        $response->with('e', $e)->setStatusCode($code)->setFormat($format)->setView($view);

        $response->send();
    }

    /**
     * 脚本结束时收集错误
     */
    public function appShutdown()
    {
        $error = error_get_last();
        if ($error) {
            $this->appException(new ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']));
        }
    }
}