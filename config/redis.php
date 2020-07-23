<?php
use think\facade\Env;
return [
    'host' => Env::get('REDIS.HOST'),
    'port' => Env::get('REDIS.PORT'),
    'auth' => Env::get('REDIS.AUTH')
];
