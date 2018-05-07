<?php

/**
 * session配置
 */
return [
    /**
     * session驱动类型，生产环境中不建议使用file，因为用文件存储的session是不会有gc的
     */
    'driver'=>'file',
    'name'=>'flySid',
    'expire'=>3600,
    'path'=>'/',
    'domain'=>'',
    'secure'=>false,
    'httpOnly'=>true,
];