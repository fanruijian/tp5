<?php
/**
 * Created by PhpStorm.
 * User: Host-0034
 * Date: 2018/7/20
 * Time: 15:18
 */

namespace app\common\utils;


use app\common\exception\JsonException;
use think\facade\Cache;

/*
 * redis  操作工具类
 */

class RedisUtils
{

    /**
     * @param string $store
     * @return \Redis
     * @throws JsonException
     */
    public static function init($store = "default")
    {
        $con = new \Redis();
        $con->connect(config('redis.host'), config('redis.port')); //连接redis
        $con->auth(config('redis.auth')); //密码验证  没有可不写
        $con->select(0); //选择数据库
        return $con;
    }

}