<?php

/**
 * FlyHttp启动配置项
 */
return [
    'host'=>'localhost',
    'port'=>9501,
    'setting'=>[
        'dispatch_mode'=>1,
        'worker_num'=>8,
        'max_request'=>10000,
        'task_worker_num'=>0,
//        'log_file'=>true,
    ]
];