<?php
namespace app\home\controller;
use think\Controller;
class Payment extends Controller
{
    protected $cpy_mchid;
    protected $cpy_appid;
    protected $cpy_secret;
    protected $cpy_key;
    public $data = null;

    public function __construct($cpy_mchid, $cpy_appid, $cpy_secret, $cpy_key)
    {
        $this->cpy_mchid = $cpy_mchid; //https://pay.weixin.qq.com 产品中心-开发配置-商户号
        $this->cpy_appid = $cpy_appid; //微信支付申请对应的公众号的APPID
        $this->cpy_secret = $cpy_secret; //微信支付申请对应的公众号的APP Key
        $this->cpy_key = $cpy_key;   //https://pay.weixin.qq.com 帐户设置-安全设置-API安全-API密钥-设置API密钥
    }

    public function toMoney($cpy_openid,$payAmount,$outTradeNo,$username,$cpy_nonce_str){
        //封装数据
        $ip = $_SERVER['REMOTE_ADDR'];
        $dataArr = array(
            'mch_appid' => $this->cpy_appid,
            'mchid' => $this->cpy_mchid,
            'nonce_str' =>$cpy_nonce_str ,//随机字符串
            'partner_trade_no' => $outTradeNo, //订单号
            'openid' => $cpy_openid,       //客户openid
            'check_name' => 'NO_CHECK',   //是否验证客户真实姓名
            're_user_name' => $username, //填写对应openid真实姓名，如果选择不验证，不必须为客户姓名
            'amount' => $payAmount*100, //以分为单位,必须大于100
            'desc' => $username.'提现余额到零钱！',
            'spbill_create_ip' => $ip,
         );
        $wxto=new Wxpay();
        $sign=$wxto->getSign($dataArr,$this->cpy_key);
        $xml = '<xml>
            <mch_appid>'.$dataArr['mch_appid'].'</mch_appid>
            <mchid>'.$dataArr['mchid'].'</mchid>
            <nonce_str>'.$dataArr['nonce_str'].'</nonce_str>
            <partner_trade_no>'.$dataArr['partner_trade_no'].'</partner_trade_no>
            <openid>'.$dataArr['openid'].'</openid>
            <check_name>'.$dataArr['check_name'].'</check_name>
            <re_user_name>'.$dataArr['re_user_name'].'</re_user_name>
            <amount>'.$dataArr['amount'].'</amount>
            <desc>'.$dataArr['desc'].'</desc>
            <spbill_create_ip>'.$dataArr['spbill_create_ip'].'</spbill_create_ip>
            <sign>'.$sign.'</sign>
        </xml>';
//        return json_encode($xml);die;
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
        $wxto->postXmlCurl($xml, $url, true);
    }
}

?>