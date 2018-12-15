<?php
namespace app\home\controller;
use think\Controller;
use think\Db;
class Alipay extends Controller
{
    /*
    配置参数
    */
    private $config = array(
        'appid' => "wxcf1dded808489e2c",//"wxcf1dded808489e2c",    /*支付宝开放平台上的应用id*/
        'mch_id' => "1440493402",//"1440493402",   /*支付宝申请成功之后邮件中的商户id*/
        'api_key' => "wxcf1dded808489e2cdsfgdgdhfg"    /*在支付宝商户平台上自己设定的api密钥 32位*/
    );

    public function getPrePayOrder()
    {

    }

}