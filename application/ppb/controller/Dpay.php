<?php
namespace app\ppb\controller;
use app\admin\controller\Index;
use think\Controller;
use app\home\controller\WeixinPay;
class Dpay extends Base{
    public function __construct()
    {
        parent::__construct();
    }
    /*
*微信支付配置
*/
    public  function getMchid(){
        $configWx=M('wx_user')->find();
        return $configWx;
    }
    public function index($sn,$goods_price,$goods_info,$type='0')
    {
        header('Content-type:text/html; Charset=utf-8');
        $config=$this->getMchid();
        //print_r($config);die;
        $mchid = $config['mchid'];          //微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送
        $appid = $config['appid'];  //微信支付申请对应的公众号的APPID
        $appKey = $config['appsecret'];   //微信支付申请对应的公众号的APP Key
        $apiKey = $config['apikey'];  //https://pay.weixin.qq.com 帐户设置-安全设置-API安全-API密钥-设置API密钥
//①、获取用户openid
        $wxPay = new \app\ppb\controller\WeixinPay($mchid,$appid,$appKey,$apiKey);
        $openId = $wxPay->GetOpenid();      //获取openid
        // print_r($openId);die;
        if(!$openId) exit('获取openid失败');
//②、统一下单
        $outTradeNo = $sn;     //你自己的商品订单号
        $payAmount = $goods_price;          //付款金额，单位:元
        $orderName = $goods_info;    //订单标题
        if($type==0){
            $notifyUrl = 'http://'.$_SERVER['HTTP_HOST'].'/index.php/ppb/Pay/WxPayNotify';     //正常商品付款成功后的回调地址
        }elseif($type==1){
            $notifyUrl = 'http://'.$_SERVER['HTTP_HOST'].'/index.php/ppb/Pay/rechargeNotify';     //充值付款成功后的回调地址
        }

        $payTime = time();      //付款时间
        $jsApiParameters = $wxPay->createJsBizPackage($openId,$payAmount,$outTradeNo,$orderName,$notifyUrl,$payTime);
        $jsApiParameters = json_encode($jsApiParameters);
        echo '<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>微信支付样例-支付</title>
    <style>
        .btnn{
            width: 80%;
            /* height: 50px; */
            border-radius: 15px;
            background-color: #FE6714;
            border: 0px #FE6714 solid;
            cursor: pointer;
            color: white;
            font-size: 16px;
            margin-top: 103px;
            padding: 14px;
        }
        p{
            margin-bottom: 0;
        }
        div{
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
        }
        .succ{
            width: 100%;
            text-align: center;
            padding: 10px 0px;
            background: #FE6714;
            color: white;
        }
    </style>
    <script type="text/javascript">
        //调用微信JS api 支付
        function jsApiCall()
        {
            WeixinJSBridge.invoke(
                    \'getBrandWCPayRequest\',';
        echo $jsApiParameters;
        echo '  ,
            function(res){
                WeixinJSBridge.log(res.err_msg);
            }
        );
        }
        function callpay()
        {
            if (typeof WeixinJSBridge == "undefined"){
                if( document.addEventListener ){
                    document.addEventListener(\'WeixinJSBridgeReady\', jsApiCall, false);
                }else if (document.attachEvent){
                    document.attachEvent(\'WeixinJSBridgeReady\', jsApiCall);
                    document.attachEvent(\'onWeixinJSBridgeReady\', jsApiCall);
                }
            }else{
                jsApiCall();
            }
        }
    </script>
</head>
<body>
<ul class="succ">
    确认信息
</ul>
<div style="background: linear-gradient(to top, #FFCC99, white);">
<div>
    <p style="color:#9ACD32;font-size: 20px;">商品名称 : </p>
    <span style="color:#f00;font-size:18px;padding-left: 9%;">';echo $orderName;
        echo '</span>
</div>
    <div>
        <p style="color:#9ACD32;font-size: 20px;">订单号 : </p>
        <span style="color:#f00;font-size:18px;padding-left: 9%;">';echo $outTradeNo;
        echo '</span>
    </div>

<div>
    <p style="color:#9ACD32;font-size: 20px;">该笔订单支付金额为 : </p>
    <span style="color:#f00;font-size:18px;padding-left: 9%;">';echo $payAmount;
        echo '</span>
</div>


    <div align="center">
        <button type="button" onclick="callpay()" class="btnn">立即支付</button>
    </div>
</div>

</body>
</html>';

    }
    //点击授权微信登录
    public function wxLogin(){
        header('Content-type:text/html; Charset=utf-8');
        $config=$this->getMchid();
        $appid = $config['appid'];  //微信支付申请对应的公众号的APPID
        $appKey = $config['appsecret'];   //微信支付申请对应的公众号的APP Key
        return json_encode(array('status'=>1,'appid'=>$appid));
    }
    //获取微信资料比较
    public function wxcallback(){
        header('Content-type:text/html; Charset=utf-8');
        $config=$this->getMchid();
        $mchid = $config['mchid'];          //微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送
        $appid = $config['appid'];  //微信支付申请对应的公众号的APPID
        $appKey = $config['appsecret'];   //微信支付申请对应的公众号的APP Key
        $apiKey = $config['apikey'];  //https://pay.weixin.qq.com 帐户设置-安全设置-API安全-API密钥-设置API密钥
        $code = $_GET["code"];
        if(!$code) exit('授权失败');
        $get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appKey.'&code='.$code.'&grant_type=authorization_code';
        $curlGet= new WeixinPay($mchid,$appid,$appKey,$apiKey);
        $res=$curlGet->curlGet($get_token_url);
        $json_obj = json_decode($res,true);
        //根据openid和access_token查询用户信息
        $access_token = $json_obj['access_token'];
        $openid = $json_obj['openid'];
        $get_user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        $res=$curlGet->curlGet($get_user_info_url);
        //解析json
        $user_obj = json_decode($res,true);
        //若没有账号
        $datas['openid']=$user_obj['openid'];
        $datas['nickname']=$user_obj['nickname'];
        $datas['head_pic']=$user_obj['headimgurl'];
        $data=M('users')->where(['openid'=>$user_obj['openid']])->getField('openid');
        if(!$data){
            $datas['sex']=$user_obj['sex'];
            $datas['reg_time']=time();
            $datas['token'] = base64_encode($datas['openid']);
            $point=M('config')->where(['name'=>'reg_integral'])->find();
            $datas['first_leader']=0;
            $datas['pay_points'] = $point['value'];
            accountLog($datas['nickname'], 0,$datas['pay_points'], '会员注册赠送积分'); // 记录日志流水
            $datad=M('users')->add($datas);
        }else{
            $datad=M('users')->where(['openid'=>$user_obj['openid']])->save($datas);
        }
        if($datad){
            return json_encode(array('status'=>1,'msg'=>'登陆成功','data'=>$datas));
        }else{
            return json_encode(array('status'=>0,'msg'=>'授权失败','data'=>''));
        }
    }

    private function ToUrlParams($urlObj)
    {
        $buff = "";
        foreach ($urlObj as $k => $v)
        {
            if($k != "sign") $buff .= $k . "=" . $v . "&";
        }
        $buff = trim($buff, "&");
        return $buff;
    }

    //提现
    public function txPrice($username="杜会先",$sn="6542135498754",$goods_price="0.01"){
        header('Content-type:text/html; Charset=utf-8');
//        $config=$this->getMchid();
//        $mchid = $config['mchid'];          //微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送
//        $appid = $config['appid'];  //微信支付申请对应的公众号的APPID
//        $appKey = $config['appsecret'];   //微信支付申请对应的公众号的APP Key
//        $apiKey = $config['apikey'];  //https://pay.weixin.qq.com 帐户设置-安全设置-API安全-API密钥-设置API密钥
        $mchid = '1507637461';          //微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送
        $appid = 'wxed2b5fcaa5e10ae2';  //微信支付申请对应的公众号的APPID
        $appKey = '0b6a81f36c5799d33d59b0d325cf8d9b';   //微信支付申请对应的公众号的APP Key
        $apiKey = 'e8vKk4E9IUHYDq59XnjKP3U0vj5htfnA';  //https://pay.weixin.qq.com 帐户设置-安全设置-API安全-API密钥-设置API密钥
        $wxPay = new WeixinPay($mchid,$appid,$appKey,$apiKey);
        $openId = $wxPay->GetOpenid();
        // print_r($openId);die;
         if(!$openId) exit('获取openid失败');
        $outTradeNo = $sn;     //你自己的商品订单号
        $payAmount = $goods_price;          //金额，单位:元
        $cpy_nonce_str = time().rand(100000,999999);   //随机字符串
        //封装数据
        $ip = $_SERVER['REMOTE_ADDR'];
        $dataArr = array(
            'mch_appid' => $appid,
            'mchid' => $mchid,
            'nonce_str' =>$cpy_nonce_str ,//随机字符串
            'partner_trade_no' => $outTradeNo, //订单号
            'openid' => $openId,       //客户openid
            'check_name' => 'NO_CHECK',   //是否验证客户真实姓名
            're_user_name' => $username, //填写对应openid真实姓名，如果选择不验证，不必须为客户姓名
            'amount' => $payAmount*100, //以分为单位,必须大于100
            'desc' => $username.'提现余额'.$payAmount.'元到微信零钱！',
            'spbill_create_ip' => $ip,
        );

        $dataArr['sign']=$this->getSign($dataArr,$apiKey);
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
        $res=$wxPay->postXmlCurl(self::arrayToXml($dataArr), $url, true);
        print_r($res);die;
        if ($res === false) {
            die('parse xml error');
        }
        if ($res->return_code != 'SUCCESS') {
            die($res->return_msg);
        }
        if ($res->result_code != 'SUCCESS') {
            die($res->err_code);
        }
        return true;
    }


    public static function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
        }
        $xml .= "</xml>";
        return $xml;
    }

    /**
     *  作用：生成签名
     *      $obj 数组
     *      $key 商户key
     */
    public function getSign($Obj,$key)
    {
        foreach ($Obj as $k => $v)
        {
            $Parameters[$k] = $v;
        }
        //签名步骤一：按字典序排序参数
        ksort($Parameters);
        $String = $this->formatBizQueryParaMap($Parameters, false);
        //签名步骤二：在string后加入KEY
        $String = $String."&key=".$key;
        //签名步骤三：MD5加密
        $String = md5($String);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($String);
        return $result;
    }

}

?>