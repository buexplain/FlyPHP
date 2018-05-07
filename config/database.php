<?php

/**
 * 数据库配置
 * 不是分布式数据库只需配置 一个 master 即可
 * 如果是多主模式的分布式数据库 则配置多个 master 即可
 * 如果是主从模式的分布式数据库 则 master 与 slave 都要配置
 */
return [
    //主库
    'master'=>[
        'test'=>[
            'driver'=>'mysql',
            'host'=>'192.168.199.95',
            'port'=>'3306',
            'dbName'=>'test',
            'charset'=>'utf8',
            'userName'=>'root',
            'password'=>'root',
        ],
    ],
    //从库
    'slave'=>[
//        'test2'=>[
//            'driver'=>'mysql',
//            'host'=>'192.168.199.95',
//            'port'=>'3306',
//            'dbName'=>'test',
//            'charset'=>'utf8',
//            'userName'=>'root',
//            'password'=>'root',
//        ],
    ],
];