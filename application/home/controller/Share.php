<?php
namespace app\home\controller;
use Think\Controller;

class Share extends Base{

    public function _initialize()
    {
        parent::_initialize();
    }
    public function jssuser(){
        $uid=I('user_id',0);
        $config=getMchid();
        $appid = $config['appid'];  //微信支付申请对应的公众号的APPID
        $appKey = $config['appkey'];   //微信支付申请对应的公众号的APP Key
        $title = $config['wxname'];   //微信分享标题
        $desc = $config['share_ticket'];   //微信分享描述
        $img = $config['headerpic'];   //分享图片
        $jssdk = new Jssdk($appid, $appKey);
        $signPackage = $jssdk->GetSignPackage();
        $data=array();
        $link='http://'.$_SERVER['HTTP_HOST'].'/index.php/home/index/reg/'.'uid/'.$uid;
        $data['title']=$title;
        $data['desc']=$desc;
        $data['img']=$img;
        $data['link']=$link;
        $data['data']=$signPackage;
        return json_encode(array('status'=>1,'data'=>$data ));
    }


}