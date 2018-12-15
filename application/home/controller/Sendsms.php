<?php
namespace app\home\controller;
use Think\Controller;
use app\common\logic\DySms;
class Sendsms extends Base{
    private $config;
    public function __construct()
    {
        $this->config = tpCache('sms') ?: [];
    }
    /**
     * 发送短信
     */
   public function sendSms($mobile='18829076882',$TemplateCode='SMS_138072655') {

        $params = array ();
        // fixme 必填
        $accessKeyId = $this->config['sms_appkey'];
        $accessKeySecret =$this->config['sms_secretKey'];

        // fixme 必填: 短信接收号码
        $params["PhoneNumbers"] = $mobile;

        // fixme 必填: 短信签名
        $params["SignName"] = "启智航创";

        // fixme 必填: 短信模板Code
        $params["TemplateCode"] = $TemplateCode;

        // fixme 可选: 设置模板参数, 假如模板中存在变量需要替换则为必填项
        $params['TemplateParam'] = Array (
            "code" => rand(1000,9999),
            "name" => $this->config['sms_product']
        );
        // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
        if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
            $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
        }
        // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
        $helper = new DySms();

        // 此处可能会抛出异常，注意catch
        $content = $helper->request(
            $accessKeyId,
            $accessKeySecret,
            "dysmsapi.aliyuncs.com",
            array_merge($params, array(
                "RegionId" => "cn-hangzhou",
                "Action" => "SendSms",
                "Version" => '2017-05-25',
            ))
        // fixme 选填: 启用https
        );
        return $content;
    }
   function index(){
       ini_set("display_errors", "on"); // 显示错误提示，仅用于测试时排查问题
// error_reporting(E_ALL); // 显示所有错误提示，仅用于测试时排查问题
       set_time_limit(0); // 防止脚本超时，仅用于测试使用，生产环境请按实际情况设置
       header("Content-Type: text/plain; charset=utf-8"); // 输出为utf-8的文本格式，仅用于测试
// 验证发送短信(SendSms)接口
       $response = Sendsms::sendSms();
       echo "发送短信(sendSms)接口返回的结果:\n";
       print_r($response);
   }

}
