<?php
namespace app\ppb\controller;

use think\Controller;
use app\common\logic\UsersLogic;
class User extends Base{
    /*
  * 登录
  */
    public function login(){
        $username = trim(I('post.mobile'));
        $password = trim(I('post.pwd'));
        if(!$username || !$password){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'请填写账号或密码'));
        }
        $user = M('users')->where("mobile",$username)->field('user_id,sex,reg_time,last_login,last_ip,mobile,password,head_pic,nickname,level')->find();
        if(isset($user['reg_time'])){
            $user['reg_time']=date('Y-m-d',$user['reg_time']);
        }
        if(isset($user['last_login']) && $user['last_login']>0){
            $user['last_login']=date('Y-m-d',$user['last_login']);
        }
        if(!$user){
            $result = array('status'=>-1,'data'=>'','msg'=>'账号不存在!');
        }elseif(md5($password) != $user['password']){
            $result = array('status'=>-2,'data'=>'','msg'=>'密码错误!');
        }elseif($user['is_lock'] == 1){
            $result = array('status'=>-3,'data'=>'','msg'=>'账号异常已被锁定！！！');
        }else{
            M('users')->where(['user_id'=>$user['user_id']])->save(array('last_login'=>time(),'last_ip'=>$this->request->ip()));
            $result = array('status'=>1,'msg'=>'登陆成功','data'=>$user);
        }
        exit(json_encode($result));
    }
    /*
      * 注册
      */
    public function reg(){
        $verify_code = I('post.verify');
        $username = I('post.mobile');
        $password = I('post.pwd');
        $leader=I('post.first_leader');
        if(empty($leader)){
            unset($leader);
        }else{
            $map['first_leader'] = $leader;
        }
        
        if(!$username || !$password){
            return array('status'=>0,'msg'=>'请输入完整信息','data'=>'');
        }
        if(isset($verify_code)){
            $time=time()-300;
            $where['add_time']=array('gt',$time);
            $where['status']=array('eq',1);
            $where['scene']=array('eq',1);
            $code=M('sms_log')->where($where)->field('code')->select();
            foreach($code as $k=>$v){
                $code[]=$v['code'];
            }
            if(!in_array($verify_code,$code)){
                return json_encode(array('status'=>0,'data'=>'','msg'=>'验证码错误,请检查'));
            }
        }else{
            return json_encode(array('status'=>0,'data'=>'','msg'=>'验证码为空'));
        }
        //验证是否存在用户名
        if(get_user_info($username,2)){
            return json_encode(array('status'=>0,'msg'=>'账号已存在','data'=>''));
        }
        $map['mobile'] = $username;
        $map['nickname'] = time().'用户';
        $map['password'] = md5($password);
        $map['reg_time'] = time();
        $map['level'] = 0;
        $map['token'] = base64_encode($username);
        $map['head_pic'] = 'http://'.$_SERVER['HTTP_HOST'].'/public/head_pic.jpg';
        $res = M('users')->add($map);
        if($res){
            return json_encode(array('status'=>1,'msg'=>'注册成功','data'=>$res));
        }else{
            return json_encode(array('status'=>0,'msg'=>'注册失败','data'=>""));
        }
    }
    //忘记密码
    public function forget_pwd()
    {
        $verify_code = I('post.verify');
        $username = I('post.mobile');
        $password = I('post.pwd');
        $password2 = I('post.pwd2');
        if($password2 != $password){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'两次密码不一致'));
        }
        if(isset($verify_code)){
            $time=time()-300;
            $where['add_time']=array('gt',$time);
            $where['status']=array('eq',1);
            $where['scene']=array('eq',1);
            $code=M('sms_log')->where($where)->field('code')->select();
            foreach($code as $k=>$v){
                $code[]=$v['code'];
            }
            if(!in_array($verify_code,$code)){
                return json_encode(array('status'=>0,'data'=>'','msg'=>'验证码错误,请检查'));
            }
        }else{
            return json_encode(array('status'=>0,'data'=>'','msg'=>'验证码为空'));
        }
        $user = M('users')->where('mobile', $username)->find();
        if ($user) {
            if(md5($password) == $user['password']){
                return json_encode(array('status'=>0,'data'=>'','msg'=>'与原密码一致，请重新输入'));
            }else{
                $res=M('users')->where("mobile", $username)->save(array('password'=>md5($password)));
                if($res){
                    $data['password']=md5($password);
                    return json_encode(array('status'=>1,'data'=>$data,'msg'=>'修改成功'));
                }else{
                    return json_encode(array('status'=>0,'data'=>'','msg'=>'修改失败，请重试'));
                }
            }
        } else {
            return json_encode(array('status'=>0,'data'=>'','msg'=>'账号错误'));
        }
    }
    /*
  * 获取（编辑）个人信息
  */
    public function getinfo(){
        $uid=I('post.user_id');
        $userLogic = new UsersLogic();
        $user_info = $userLogic->get_info($uid); // 获取用户信息
        //客服电话
        $user_info['kf_phone']=tpCache('shop_info.phone');
        if($user_info){
            return json_encode(array('status'=>1,'data'=>$user_info,'msg'=>'获取成功'));
        }else{
            return json_encode(array('status'=>0,'data'=>'','msg'=>'获取失败'));
        }
    }
//保存修改信息
    public function setinfo()
    {
        $uid=I('post.user_id');
        if(empty($uid)){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'参数错误'));
        }
        $users=M('users')->where(['user_id'=>$uid])->find();
        $userLogic = new UsersLogic();
        I('post.nickname')?$data['nickname']=I('post.nickname'):$users['nickname'];
        I('post.sex')?$data['sex']=I('post.sex'):$users['sex'];
        I('post.district')?$data['district']=I('post.district'):$users['district'];
        $file =request()->file('head_pic');
        $info=$this->upload($file);
        $info?$data['head_pic']=$info:$data['head_pic']=$users['head_pic'];

        if (!empty($data['email'])) {
            $c = M('users')->where(['email' => $data['email'], 'user_id' => ['<>', $uid]])->count();
            if($c>0){
                return json_encode(array('status'=>0,'data'=>'','msg'=>'邮箱已被使用'));
            }
        }

        $res=M('users')->where(['user_id'=>$uid])->save($data);
        if (!$res){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'修改失败'));
        }else{
            return json_encode(array('status'=>1,'data'=>'','msg'=>'修改成功'));
        }
    }
    public function upload($file){
        if($file){
            $info = $file->move('public/upload/qr_code');
            if($info){
                return $this->request->domain().'/public/upload/qr_code/'.$info->getSaveName();
            }else{
                echo $file->getError();
            }
        }
    }
    /*
  * 用户收货地址列表
  */
    public function address_list(){
        $uid=I('get.user_id');
        $address_lists = get_user_address_list($uid);
        foreach($address_lists as $k=>$v){
            $address_lists[$k]['twon']=$this->trimall($v['twon']);
        }
        if($address_lists){
            return json_encode(array('status'=>1,'data'=>$address_lists,'msg'=>'获取成功'));
        }else{
            return json_encode(array('status'=>0,'data'=>'','msg'=>'暂无收货地址'));
        }
    }
    public function trimall($str)//删除空格
    {
        $oldchar=array("","　","\t","\n","\r");
        $newchar=array("","","","","");
        return str_replace($oldchar,$newchar,$str);
    }
    /*
     * 添加地址
     */
    public function add_address(){
        header("Content-type:text/html;charset=utf-8");
        $datas['user_id']=I('post.user_id');
        $datas['consignee']=I('post.consignee');
        $datas['province']=I('post.province');
        $datas['city']=I('post.city');
        $datas['twon']=I('post.twon');
        $datas['address']=I('post.address');
//        $datas['is_default']=I('post.is_default');
        $datas['mobile']=I('post.mobile');
//        $datas['zipcode']=I('post.zipcode');
        $logic = new UsersLogic();
        $data = $logic->add_address($datas['user_id'],0,$datas);
        $this->ajaxReturn($data);

    }

    /*
     * 获取编辑地址
     */
    public function edit_address(){
        header("Content-type:text/html;charset=utf-8");
        $id = I('get.address_id/d');
        $uid=I('get.user_id');
        $address = M('user_address')->where(array('address_id'=>$id,'user_id'=> $uid))->find();
        $ress=getAddress($address['province'],$address['city'],$address['twon']);
        $address['province']=$ress[0]['name'];
        $address['city']=$ress[1]['name'];
        $address['twon']=$ress[2]['name'];

        if(!$address){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'获取失败'));
        }else{
            return json_encode(array('status'=>1,'data'=>$address,'msg'=>'获取成功'));
        }
    }
//    保存编辑
    public function update_address()
    {
        header("Content-type:text/html;charset=utf-8");
        $id = I('post.address_id/d');
        $uid=I('post.user_id');
        $users= M('user_address')->where(array('address_id'=>$id,'user_id'=> $uid))->find();
//        I('post.user_id')?$data['user_id']=I('post.user_id'):$users['user_id'];
        I('post.consignee')?$data['consignee']=I('post.consignee'):$users['consignee'];
        I('post.province')?$data['province']=I('post.province'):$users['province'];
        I('post.city')?$data['city']=I('post.city'):$users['city'];
        I('post.address')?$data['address']=I('post.address'):$users['address'];
        I('post.twon')?$data['twon']=I('post.twon'):$users['twon'];
//        I('post.is_default')?$data['is_default']=I('post.is_default'):$users['is_default'];
        I('post.mobile')?$data['mobile']=I('post.mobile'):$users['mobile'];
        $logic = new UsersLogic();
        $data = $logic->add_address($uid,$id,$data);
        $this->ajaxReturn($data);
    }
    /*
     * 设置默认收货地址
     */
    public function set_default(){
        $id = I('get.address_id/d');
        $uid = I('get.user_id/d');
        M('user_address')->where(array('user_id'=>$uid))->save(array('is_default'=>0));
        $row = M('user_address')->where(array('user_id'=>$uid,'address_id'=>$id))->save(array('is_default'=>1));
        if(!$row){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'设置失败'));
        }else{
            return json_encode(array('status'=>1,'data'=>'','msg'=>'设置成功'));
        }
    }

    /*
     * 地址删除
     */
    public function del_address(){
        $id = I('get.address_id/d');
        $uid = I('get.user_id/d');
        $address = M('user_address')->where("address_id", $id)->find();
        if($address['is_default'] == 1)
        {
            // 如果删除的是默认收货地址 则要把第一个地址设置为默认收货地址
            $address2 = M('user_address')->where("user_id", $uid)->find();
            $address2 && M('user_address')->where("address_id", $address2['address_id'])->save(array('is_default'=>1));
        }
        $row = M('user_address')->where(array('user_id'=>$uid ,'address_id'=>$id))->delete();
        if(!$row){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'操作失败'));
        }else{
            return json_encode(array('status'=>1,'data'=>'','msg'=>'操作成功'));
        }
    }
    public function jssuser(){
        $uid=I('user_id',0);
        $config=getMchid();
        $appid = $config['appid'];  //微信支付申请对应的公众号的APPID
        $appKey = $config['appsecret'];   //微信支付申请对应的公众号的APP Key
        $title = $config['wxname'];   //微信分享标题
        $desc = $config['share_ticket'];   //微信分享描述
        $img = $config['headerpic'];   //分享图片
        $link='http://'.$_SERVER['HTTP_HOST'].'/index.php/ppb/ppb/zhuce/last_uid/'.$uid;//分享链接
        $jssdk = new Jssdk($appid, $appKey);
        $signPackage = $jssdk->GetSignPackage();
        $signPackage['desc']=$desc;
        $signPackage['title']=$title;
        $signPackage['img']=$img;
        $signPackage['link']=$link;
        $this->assign('data',$signPackage);
        return $this->fetch();
        // return json_encode(array('status'=>1,'data'=>$signPackage ));
    }

}