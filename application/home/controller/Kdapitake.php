<?php
namespace app\home\controller;

use think\Controller;

class Kdapitake extends Base{
    //预约取件
    public function index(){
        //构造在线下单提交信息
        $data=I('post.');
        $order=new Kdapiorder();
        $logis=$order->getOrderTracesByJson($data['OrderCode']);
        $eorder = [];
        $eorder["ShipperCode"] = "$logis";
        $eorder["OrderCode"] = "$data[OrderCode]";
        $eorder["PayType"] = 1;
        $eorder["ExpType"] = 1;
        $sender = [];
        $sender["Name"] = "$data[send_Name]";
        $sender["Mobile"] = "$data[send_Mobile]";
        $sender["ProvinceName"] = "$data[send_ProvinceName]";
        $sender["CityName"] = "$data[send_CityName]";
        $sender["ExpAreaName"] = "$data[send_ExpAreaName]";
        $sender["Address"] = "$data[send_Address]";

        $receiver = [];
        $receiver["Name"] = "$data[send_Name]";
        $receiver["Mobile"] = "$data[Receiver_Mobile]";
        $receiver["ProvinceName"] = "$data[Receiver_ProvinceName]";
        $receiver["CityName"] = "$data[Receiver_CityName]";
        $receiver["ExpAreaName"] = "$data[Receiver_ExpAreaName]";
        $receiver["Address"] = "$data[Receiver_Address]";

        $commodityOne = [];
        $commodityOne["GoodsName"] = "$data[commodityOne_GoodsName]";
        $commodity = [];
        $commodity[] = $commodityOne;

        $eorder["Sender"] = $sender;
        $eorder["Receiver"] = $receiver;
        $eorder["Commodity"] = $commodity;

//调用在线下单
        $jsonParam = json_encode($eorder, JSON_UNESCAPED_UNICODE);
        $jsonResult = $this->submitOOrder($jsonParam);

//解析在线下单返回结果
        $result = json_decode($jsonResult, true);
        if($result["ResultCode"] == "100") {
            return json_encode(array('status'=>1,'msg'=>'成功'));
        }else {
            return json_encode(array('status'=>0,'msg'=>'失败'));
        }
    }
/**
 * Json方式 提交在线下单
 */
function submitOOrder($requestData){
    $datas = array(
        'EBusinessID' => C('WULIU.EBusinessID'),
        'RequestType' => '1001',
        'RequestData' => urlencode($requestData) ,
        'DataType' => '2',
    );
    $datas['DataSign'] = $this->encrypt($requestData, C('WULIU.AppKey'));
    $result=$this->sendPost(C('WULIU.ReqURLTake'), $datas);
    return $result;
}



/**
 *  post提交数据
 * @param  string $url 请求Url
 * @param  array $datas 提交的数据
 * @return url响应返回的html
 */
function sendPost($url, $datas) {
    $temps = array();
    foreach ($datas as $key => $value) {
        $temps[] = sprintf('%s=%s', $key, $value);
    }
    $post_data = implode('&', $temps);
    $url_info = parse_url($url);
    $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
    $httpheader.= "Host:" . $url_info['host'] . "\r\n";
    $httpheader.= "Content-Type:application/x-www-form-urlencoded\r\n";
    $httpheader.= "Content-Length:" . strlen($post_data) . "\r\n";
    $httpheader.= "Connection:close\r\n\r\n";
    $httpheader.= $post_data;
    $fd = fsockopen($url_info['host'], $url_info['port']);
    fwrite($fd, $httpheader);
    $gets = "";
    $headerFlag = true;
    while (!feof($fd)) {
        if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
            break;
        }
    }
    while (!feof($fd)) {
        $gets.= fread($fd, 128);
    }
    fclose($fd);

    return $gets;
}

/**
 * 电商Sign签名生成
 * @param data 内容
 * @param appkey Appkey
 * @return DataSign签名
 */
function encrypt($data, $appkey) {
    return urlencode(base64_encode(md5($data.$appkey)));
}
}