<?php namespace fly\contracts\http;

interface Error
{
    public function appError($type, $message, $file, $line);
    public function appException($e);
    public function appShutdown();
}