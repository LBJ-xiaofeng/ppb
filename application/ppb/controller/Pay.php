<?php
namespace app\ppb\controller;
use think\Controller;
use think\Request;
use think\Db;
class Pay extends Base
{

//微信付款订单回调方法
    public function WxPayNotify()
    {
//微信返回的数据
        $input = file_get_contents("php://input");
        $myfile = fopen("wxtestfile.txt", "a");
        fwrite($myfile, "\r\n");
        fwrite($myfile, $input);
        if($input){
            $xml = simplexml_load_string($input);
            $money = (string)$xml->total_fee;
            $return_code = (string)$xml->return_code;
            $attach = (string)$xml->attach;
            $user_id = (string)$xml->user_id;
            $out_trade_no = (string)$xml->out_trade_no;
        }
        if($return_code){
            $order['pay_status']=1;
            $order['pay_time']=time();
            M('order')->where(['order_sn'=>$out_trade_no])->save($order);
            echo exit('<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>'); 
        }else{
             echo '付款失败';
        }

    }
    //微信充值回调方法
    public function rechargeNotify()
    {
//微信返回的数据
        $input = file_get_contents("php://input");
        $myfile = fopen("wxtestfile.txt", "a");
        fwrite($myfile, "\r\n");
        fwrite($myfile, $input);
        if($input){
            $xml = simplexml_load_string($input);
            $money = (string)$xml->total_fee;
            $return_code = (string)$xml->return_code;
            $attach = (string)$xml->attach;
            $user_id = (string)$xml->user_id;
            $out_trade_no = (string)$xml->out_trade_no;
        }
        if($return_code){
            $user=M('recharge')->where(['order_sn'=>$out_trade_no])->getField('user_id');
            M('recharge')->where(['order_sn'=>$out_trade_no])->save(array('pay_time'=>time(),'pay_status'=>1));
            M('users')->where(['user_id'=>$user])->setInc('user_money',round($money/100,2)+$users);
            echo exit('<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>'); 
        }else{
            echo exit('<xml><return_code><![CDATA[ERROR]]></return_code><return_msg><![CDATA[NO]]></return_msg></xml>'); 
        }

    }


}