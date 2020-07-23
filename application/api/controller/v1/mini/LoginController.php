<?php

namespace app\mini\controller;

use app\mini\Base;
use think\console\Input;
use wlt\wxmini\WXLoginHelper;
use app\common\utils\RedisUtils;
use app\common\utils\WXBizDataCrypt;
use app\common\model\User;
use app\common\vo\ResultVo;
use app\common\enums\ErrorCode;
use think\Db;

/**
 * 权限相关Base
 */
class LoginController extends Base
{
    public function index(){
        $code = htmlspecialchars_decode(Input('code'));
        $data = Input('data');
        $rawData = $data['rawData'];
        $data2 = json_decode($rawData,true);
        $signature = $data['signature'];
        $iv = $data['iv'];
        $encryptedData = $data['encryptedData'];
        $userInfo = $data['userInfo'];
        $data = $this->checkLogin($code, $rawData, $signature, $encryptedData, $iv);
        $resData = [];
        $phone = '';
        if(isset($data['openId']) && isset($data['sessionKey'])){
            //保存用户信息
            $userModel = new User();
            $fuser = User::where('openid',$data['openId'])->find();
            if($fuser){
                $userModel = $fuser;
                $phone = $fuser->phone;
                $userModel->update_time = date('Y-m-d H:i:s',time());
            }else{
                $userModel->create_time = date('Y-m-d H:i:s',time());
                $userModel->update_time = date('Y-m-d H:i:s',time());
            }
            $userModel->openid = $data['openId'];
            $userModel->union_id = '';
            $userModel->nickname = $data2['nickName'];
            $userModel->avatarurl = $data2['avatarUrl'];
            $userModel->gender = $data2['gender'];
            $userModel->country = $data2['country']; 
            $userModel->province = $data2['province'];
            $userModel->city = $data2['city'];
            $userModel->language = $data2['language'];
            $save = $userModel->save();
            //并且将openid和sessionkey放入缓存
            $redis = RedisUtils::init();
            $redis->hset($data['session3rd'],'userId',$userModel->id);
            $redis->hset($data['session3rd'],'openId',$data['openId']);
            $redis->hset($data['session3rd'],'sessionKey',$data['sessionKey']);
            // $p = $redis->hgetall($data['session3rd']);
            $resData['openid'] = $data['openId'];
            $resData['token'] = $data['session3rd'];
            // $phone = $userModel->phone;
            $resData['phone'] = $phone;
        }
        return ResultVo::success($resData);
    }

    public function checkLogin($code, $rawData, $signature, $encryptedData, $iv){
        $appid = config('wx.appid');
        $appsecret = config('wx.secret');
        $grant_type = config("grant_type");
        $url = "https://api.weixin.qq.com/sns/jscode2session?"."appid=".$appid."&secret=".$appsecret."&js_code=".$code."&grant_type=".$grant_type;
        $res = json_decode($this->httpGet($url),true);
        $sessionKey = $res['session_key']; //取出json里对应的值
        $signature2 = sha1(htmlspecialchars_decode($rawData).$sessionKey);
        if($signature==$signature2){
            $data['openId'] = $res['openid'];
            $data['sessionKey'] = $res['session_key'];
            $data['session3rd'] = $this->getSession3rd();
        }else{
            return ResultVo::error(ErrorCode::SIGNATURE_FAILED);
        }
        return $data;
    }

    public function httpGet($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);
        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
    }


    public function getSession3rd(){
        $charid = strtoupper(md5(uniqid(mt_rand(), true)));
        return substr($charid, 0, 8) . substr($charid, 8, 4) . substr($charid, 12, 4) . substr($charid, 16, 4) . substr($charid, 20, 12);
    }

    public function phone(){
        // 行为逻辑
        $token = input('server.HTTP_TOKEN');
        
        $sessionArr = $this->getSession($token);
        $appid = config('wx.appid');
        $sessionKey = $sessionArr['sessionKey'];
        $userId = $sessionArr['userId'];
        $encryptedData = Input('encryptedData');
        $iv = input('iv');

        $pc = new WXBizDataCrypt($appid, $sessionKey);
        $pc->decryptData($encryptedData, $iv, $data );
        $phoneArr = json_decode($data,true);
        $phone = $phoneArr['phoneNumber'];
        $resData['phone'] = $phone;

        // 更新用户表的phone
        $sql = "update user set phone=".$phone." where id=".$userId;
        $result = Db::execute($sql);


        return ResultVo::success($resData);
    }


}