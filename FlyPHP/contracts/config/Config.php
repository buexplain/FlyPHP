<?php namespace fly\contracts\config;

interface Config
{
    public function __construct($path);
    public function get($name);
}