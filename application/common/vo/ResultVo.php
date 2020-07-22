<?php

namespace app\common\vo;

use think\exception\HttpResponseException;

class ResultVo
{

    /**
     * 错误码
     * @var
     */
    public $code;

    /**
     * 错误信息
     * @var
     */
    public $message;

    /**
     * data
     * @var
     */
    public $data;

    private function __construct($code, $message, $data)
    {
        $this->code = $code;
        $this->message = $message;
        $this->data = $data;
    }

    /**
     * 请求成功的方法
     * @param $data
     * @return \think\response\Json
     */
    public static function success($data = null)
    {
        if (empty($data)) {
            $data = [];
        }
        $instance = new self(0, "success", $data);
        return json($instance);
    }

    /**
     * 请求错误
     * @param $code
     * @param null $message
     * @return \think\response\Json
     */
    public static function error($code, $message = null, $statusCode=200)
    {
        if (is_array($code)) {
            $message = isset($code['message']) && $message == null ? $code['message'] : $message;
            $statusCode = isset($code['statusCode']) ? $code['statusCode'] : 200;
            $code = isset($code['code']) ? $code['code'] : null;
        }
        $instance = new self($code, $message, new \stdClass());
        if($statusCode==200){
            return json($instance);
        }else{
            throw new HttpResponseException(json(($instance), $statusCode));
        }
    }

}