<?php namespace fly\contracts\http;

interface View
{
    public function render($view, $__fly_data=[], $mergeData=[]);
    public function flush();
}