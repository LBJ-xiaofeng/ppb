<?php
namespace app\home\controller;

use think\Controller;

class Kdapiquery extends Base{
    //查询物流
    /**
     * Json方式 查询订单物流轨迹
     */
   public function getOrderTracesByJson(){
      $code=I('LogisticCode');
       $order=new Kdapiorder();
       $logis=$order->getOrderTracesByJson($code);
       $data=[];
       $requestData="{".
           "'ShipperCode':'$logis',".
           "'LogisticCode':'$code',".
           "'Sender':".
           "{".
           "'Company':'LV','Name':'$data[send_Name]','Mobile':'$data[send_Mobile]','ProvinceName':'$data[send_ProvinceName]','CityName':'$data[send_CityName]','ExpAreaName':'$data[send_ExpAreaName]','Address':'$data[send_Address]'},".
           "'Receiver':".
           "{".
           "'Company':'GCCUI','Name':'$data[Receiver_Name]','Mobile':'$data[Receiver_Mobile]','ProvinceName':'$data[Receiver_ProvinceName]','CityName':'$data[Receiver_CityName]','ExpAreaName':'$data[Receiver_ExpAreaName]','Address':'$data[Receiver_Address]'},".
           "}";
        $datas = array(
            'EBusinessID' => C('WULIU.EBusinessID'),
            'RequestType' => '1002',
            'RequestData' => urlencode($requestData) ,
            'DataType' => '2',
        );
        $datas['DataSign'] = $this->encrypt($requestData, C('WULIU.AppKey'));
        $result=$this->sendPost( C('WULIU.ReqURL'), $datas);
       $res=(json_decode($result)->Traces);
       if($res){
           return json_encode(array('status'=>1,'data'=>$res,'msg'=>'获取成功'));
       }else{
           return json_encode(array('status'=>0,'data'=>'','msg'=>'暂无快递信息'));
       }
    }

    /**
     *  post提交数据
     * @param  string $url 请求Url
     * @param  array $datas 提交的数据
     * @return url响应返回的html
     */
    public function sendPost($url, $datas) {
        $temps = array();
        foreach ($datas as $key => $value) {
            $temps[] = sprintf('%s=%s', $key, $value);
        }
        $post_data = implode('&', $temps);
        $url_info = parse_url($url);
        if(empty($url_info['port']))
        {
            $url_info['port']=80;
        }
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
    public function encrypt($data, $appkey) {
        return urlencode(base64_encode(md5($data.$appkey)));
    }
}