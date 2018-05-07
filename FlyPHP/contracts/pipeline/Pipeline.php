<?php namespace fly\contracts\pipeline;

interface Pipeline
{
    public function __construct(\fly\contracts\container\Container $app, $handle);
    public function send($data);
    public function through(array $pipes);
    public function then(\Closure $first);
}