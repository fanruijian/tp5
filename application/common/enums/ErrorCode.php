<?php
// +----------------------------------------------------------------------
// | ThinkPHP 5 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 .
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 黎明晓 <lmxdawn@gmail.com>
// +----------------------------------------------------------------------

namespace app\common\enums;

/**
 * 后台系统错误码
 * Class ErrorCode
 * @package app\common\model
 */
class ErrorCode
{

    // +----------------------------------------------------------------------
    // | 系统级错误码
    // +----------------------------------------------------------------------
    const NOT_NETWORK = [ 'code' => 1, 'message' => '系统繁忙，请稍后再试。'];

    // +----------------------------------------------------------------------
    // | 服务级错误码
    // +----------------------------------------------------------------------
    const LOGIN_FAILED = [ 'code' => 2, 'message' => '登录失效'];
    const HTTP_METHOD_NOT_ALLOWED = [ 'code' => 3, 'message' => '网络请求不予许'];
    const VALIDATION_FAILED = [ 'code' => 4, 'message' => '身份验证失败','statusCode'=>'401'];
    const USER_AUTH_FAIL = [ 'code' => 5, 'message' => '用户名或者密码错误','statusCode'=>'403'];
    const USER_NOT_PERMISSION = [ 'code' => 6, 'message' => '当前没有权限登录'];
    const AUTH_FAILED = [ 'code' => 7, 'message' => '权限验证失败'];
    const DATA_CHANGE = [ 'code' => 8, 'message' => '数据没有任何更改'];
    const DATA_REPEAT = [ 'code' => 9, 'message' => '数据重复'];
    const DATA_NOT = [ 'code' => 10, 'message' => '数据不存在'];
    const DATA_VALIDATE_FAIL = [ 'code' => 11, 'message' => '数据验证失败'];
    const SIGNATURE_FAILED = [ 'code' => 12, 'message' => '签名校验错误'];
    const SEND_FAILED = [ 'code' => 13, 'message' => '发送失败'];
    const SAVE_FAILED = [ 'code' => 14, 'message' => '保存失败'];
    const UP_FAILED = [ 'code' => 15, 'message' => '上传失败'];
    const VALIDATION_CODE = [ 'code' => 16, 'message' => '无效的验证码'];
    const ERROR_CODE = [ 'code' => 17, 'message' => '验证码错误'];
    const GET_ERROR = [ 'code' => 18, 'message' => '获取失败'];
    const REMINDER_ERROR = [ 'code' => 19, 'message' => '今日已催单'];
    // 管理员相关

}
