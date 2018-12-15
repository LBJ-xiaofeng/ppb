<?php
namespace app\home\controller;
use think\Controller;
use app\common\logic\lib\YLYOpenApiClient;
use app\common\logic\lib\YLYTokenClient;
use app\common\logic\lib\YLYConfigClient;

class Printdemo extends Base{

    public function index(){
        $api = new YLYOpenApiClient();
         if(session('access_token')==''){
             session('access_token',json_decode($this->getToken())->body->access_token);
             session('refresh_token',json_decode($this->getToken())->body->refresh_token);
             cookie('access_token',json_decode($this->getToken())->body->access_token);
             cookie('refresh_token',json_decode($this->getToken())->body->refresh_token);
         }
        $content = '1111';                              //打印内容
        $machineCode = YLYConfigClient::$TERMINALNUMBER;                  //授权的终端号
        $accessToken = session('access_token');      //api访问令牌
        $originId = uniqid();                         //商户自定义id
        $timesTamp = time();                         //当前服务器时间戳(10位)
        return $api->printIndex($machineCode,$accessToken,$content,$originId,$timesTamp);
    }
//获取token;
    public function getToken(){
        $token = new YLYTokenClient();
        $grantType = 'client_credentials';  //自有模式(client_credentials) || 开放模式(authorization_code)
        $scope = 'all';                     //权限
        $timesTamp = time();                //当前服务器时间戳(10位)
//        $code = '';                       //开放模式(商户code)
        return $token->GetToken($grantType,$scope,$timesTamp);
    }
    //刷新token;
    public function refreToken(){
        $token = new YLYTokenClient();
        $grantType = 'refresh_token';       //自有模式或开放模式一致
        $scope = 'all';                     //权限
        $timesTamp = time();                //当前服务器时间戳(10位)
        $RefreshToken = session('refresh_token');;                 //刷新token的密钥
        return $token->RefreshToken($grantType,$scope,$timesTamp,$RefreshToken);
    }


}