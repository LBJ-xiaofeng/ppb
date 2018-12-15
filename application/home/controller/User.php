<?php
namespace app\home\controller; 
use app\common\logic\MessageLogic;
use app\common\logic\OrderLogic;
use app\common\logic\UsersLogic;
use app\common\logic\CartLogic;
use think\Page;
use think\Verify;
use think\Db;
use think\Request;
use think\IpLocation;
use think\File;
class User extends Base{
    const CHECK_TOKEN='asDFgtRewq';  //设置验证token
    const FILE_SIZE = 3145728;
	public $user_id = 0;
	public $user = array();
	
//    public function _initialize() {
//        parent::_initialize();
//    }

    /*
     * 用户中心首页
     */
    public function index(){
        $logic = new UsersLogic();
        $user = $logic->get_info($this->user_id);
        $user = $user['result'];
        $level = M('user_level')->select();
        $level = convert_arr_key($level,'level_id');
        $this->assign('level',$level);
        $this->assign('user',$user);
        return $this->fetch();
    }


    public function logout(){
    	setcookie('uname','',time()-3600,'/');
    	setcookie('cn','',time()-3600,'/');
    	setcookie('user_id','',time()-3600,'/');
        setcookie('PHPSESSID','',time()-3600,'/');
        session_unset();
        session_destroy();
        //$this->success("退出成功",U('Home/Index/index'));
        $this->redirect('Home/Index/index');
        exit;
    }

    /*
     * 账户资金
     */
    public function account(){
        $user = session('user');
        //获取账户资金记录
        $logic = new UsersLogic();
        $data = $logic->get_account_log($this->user_id,I('get.type'));
        $account_log = $data['result'];

        $this->assign('user',$user);
        $this->assign('account_log',$account_log);
        $this->assign('page',$data['show']);
        $this->assign('active','account');
        return $this->fetch();
    }
    //申请衣物
    public function applyForClothes(){
        $data['user_id']=I('user_id');
        $data['name']=I('name');
        $data['mobile']=I('mobile');
        $data['province']=I('provice');
        $data['title']=I('title');
        $data['help']=I('content');
        $file = request()->file('img');
//        $img=request()->file('img');
//        return json_encode(array('status'=>0,'msg'=>'dgjf','data'=>$img));
        $images[]=$this->upload($file);
        $data['img']=json_encode($images);
        $data['status']=0;
        $data['add_time']=time();
        if(empty($data['name']) || empty($data['mobile']) || empty($data['province']) || empty($data['title']) || empty($data['help'])){
            return json_encode(array('status'=>0,'msg'=>'请填写完整资料','data'=>''));
        }
        $res=M('apply')->add($data);
        if($res){
            return json_encode(array('status'=>1,'msg'=>'申领成功，等待审核！','data'=>''));
        }else{
            return json_encode(array('status'=>0,'msg'=>'申领失败，请重试','data'=>''));
        }
    }
    //领取证书
    public function getCertificate(){
        $uid=I('user_id');
        if(!$uid){
            return json_encode(array('status'=>0,'msg'=>'参数错误','data'=>''));
        }
        $user=M('users')->where('user_id',$uid)->field('user_id,nickname,is_certificate')->find();
        if(!$user){
            return json_encode(array('status'=>0,'msg'=>'system busy','data'=>''));
        }
        if($user['is_certificate']==1){
            return json_encode(array('status'=>0,'msg'=>'您已领取过，请勿重复领取','data'=>''));
        }
        $res=M('users')->where('user_id',$uid)->seve(array('is_certificate'=>1,'certificate_time'=>time()));
        if($res){
            return json_encode(array('status'=>1,'msg'=>'领取成功','data'=>''));
        }else{
            return json_encode(array('status'=>0,'msg'=>'领取失败，请重试','data'=>''));
        }
    }
    //我的证书
    public function myCertificate(){
        $uid=I('user_id');
        if(!$uid){
            return json_encode(array('status'=>0,'msg'=>'参数错误','data'=>''));
        }
        $user=M('users')->where('user_id',$uid)->field('user_id,nickname,is_certificate,certificate_time')->find();
        if(!$user){
            return json_encode(array('status'=>0,'msg'=>'system busy','data'=>''));
        }
        if($user['is_certificate']==0){
            return json_encode(array('status'=>0,'msg'=>'暂无证书','data'=>''));
        }
        $res=M('ad')->where('pid',7)->getField('ad_code');
        $data['img']='http://'.$_SERVER['HTTP_HOST'].$res;
        $data['add_time']=date('Y-m-d H:i:s',$user['certificate_time']);
            return json_encode(array('status'=>1,'msg'=>'领取成功','data'=>$data));
    }
    //我的号召力
    public function myAppeal(){
        $uid=I('user_id');
        if(!$uid){
            return json_encode(array('status'=>0,'msg'=>'参数错误','data'=>''));
        }
        //今日数
        $thisCount=M('users')->where('reg_time','gt',strtotime(date('Y-m-d 0:0:0',time())))->where('reg_time','lt',strtotime(date('Y-m-d 23:59:59',time())))->where('first_leader',$uid)->count();
        //本周数
        $WeekCount=M('users')->where('reg_time','gt',strtotime(date('Y-m-d 0:0:0', strtotime("this week Monday", time()))))->where('reg_time','lt',strtotime(date('Y-m-d 23:59:59', strtotime("this week Sunday", time()))) + 24 * 3600 - 1)->where('first_leader',$uid)->count();
        //本月数
        $MonthCount=M('users')->where('reg_time','gt', mktime(0, 0, 0, date('m'), 1, date('Y')))->where('reg_time','lt',mktime(23, 59, 59, date('m'), date('t'), date('Y')))->where('first_leader',$uid)->count();
        //总数
        $count=$thisCount+$WeekCount+$MonthCount;
        $data=M('users')->where(['first_leader'=>$uid])->order('reg_time desc')->field('reg_time,user_id,nickname')->select();
        foreach($data as $k=>$v){
            $data[$k]['reg_time']=date('Y-m-d',$v['reg_time']);
        }
        $datas['thisCount']=$thisCount;
        $datas['WeekCount']=$WeekCount;
        $datas['MonthCount']=$MonthCount;
        $datas['count']=$count;
        $datas['data']=$data;
        if($datas){
            return json_encode(array('status'=>1,'msg'=>'获取成功','data'=>$datas));
        }else{
            return json_encode(array('status'=>0,'msg'=>'暂无数据','data'=>''));
        }

    }
    //点亮环保大使
    public function clickEnvironmental(){
        $uid=I('user_id');
        $count=M('users')->where(['first_leader'=>$uid])->count();
        $yq=tpCache('basic.is_remind');
        if($count<$yq){
            return json_encode(array('status'=>0,'msg'=>'您还未达到点亮的要求','data'=>''));
        }
        $res=M('users')->where('user_id',$uid)->setfield('is_lock',1);
        if($res){
            return json_encode(array('status'=>1,'msg'=>'点亮成功','data'=>''));
        }else{
            return json_encode(array('status'=>0,'msg'=>'点亮失败','data'=>''));
        }
    }
    /*
     * 优惠券列表
     */
    public function coupon(){
        $logic = new UsersLogic();
        $data = $logic->get_coupon($this->user_id,I('type'));
        foreach($data['result'] as $k =>$v){
            $user_type = $v['use_type'];
            $data['result'][$k]['use_scope'] = C('COUPON_USER_TYPE')["$user_type"];
            if($user_type==1){ //指定商品
                $data['result'][$k]['goods_id'] = M('goods_coupon')->field('goods_id')->where(['coupon_id'=>$v['cid']])->getField('goods_id');
            }
            if($user_type==2){ //指定分类
                $data['result'][$k]['category_id'] = Db::name('goods_coupon')->where(['coupon_id'=>$v['cid']])->getField('goods_category_id');
            }
        }
        $coupon_list = $data['result'];
        $this->assign('coupon_list',$coupon_list);
        $this->assign('page',$data['show']);
        $this->assign('active','coupon');
        return $this->fetch();
    }
    /**
 *  登录
 */
    public function login(){
        if($this->user_id > 0){
            $this->redirect('Home/User/index');
        }
        $referurl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : U("Home/User/index");
        $this->assign('referurl',$referurl);
        return $this->fetch();
    }

    /**
     *  登录页面图片
     */
    public function loginpage(){
        $token=I('post.token');
        if($token !=self::CHECK_TOKEN){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        $img=M('ad')->where(['pid'=>1,'enabled'=>1])->order('orderby desc')->field('ad_id,ad_code,bgcolor')->limit(1)->find();
        $img['ad_code']='http://'.$_SERVER['HTTP_HOST'].$img['ad_code'];
        return json_encode(array('status'=>1,'data'=>$img,'msg'=>'成功'));
    }

    public function pop_login(){
    	if($this->user_id > 0){
            $this->redirect('Home/User/index');
    	}
        $referurl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : U("Home/User/index");
        $this->assign('referurl',$referurl);
    	return $this->fetch();
    }
    
    public function do_login(){
        $token=I('post.token');
        if($token !=self::CHECK_TOKEN){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        $username = trim(I('post.mobile'));
        $password = trim(I('post.pwd'));
//    	$verify_code = I('post.verify');
//        $verify = new Verify();
//        if (!$verify->check($verify_code))
//        {
//            return json_encode(array('status'=>0,'data'=>'','msg'=>'验证码错误'));
//        }
    	$logic = new UsersLogic();
    	$res = $logic->login($username,$password);
    	if($res['status'] == 1){
            $orderLogic = new OrderLogic();
            $orderLogic->setUserId($res['data']['user_id']); //登录后将超时未支付订单给取消掉
            $orderLogic->abolishOrder($res['data']['user_id']);
    	}
    	exit(json_encode($res));
    }
    /**
     *  注册
     */
    public function reg(){
        header('Content-type:text/html; Charset=utf-8');
        $token=I('post.token');
        if($token !=self::CHECK_TOKEN){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        $verify_code = I('post.verify');
        if(isset($verify_code)){
            $time=time()-300;
            $where['add_time']=array('gt',$time);
            $where['status']=array('eq',1);
            $where['scene']=array('eq',1);
            $code=M('sms_log')->where($where)->getField('code');
            if($verify_code != $code){
                return json_encode(array('status'=>0,'data'=>'','msg'=>'验证码错误,请检查'));
            }
        }
        $username = I('post.mobile');
        $password = I('post.pwd');
        $password2 = I('post.pwd2');
        $email = I('post.email');
        $logic = new UsersLogic();
        $data = $logic->reg($username,$password,$password2,$email,0);
        $this->ajaxReturn($data);
        exit;
    }

    /*
     * 用户收货地址列表
     */
    public function address_list(){
        $token=I('get.token');
        if($token !=self::CHECK_TOKEN){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
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
        $token=I('post.token');
        if($token !=self::CHECK_TOKEN){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        $datas['user_id']=I('post.user_id');
        $datas['consignee']=I('post.consignee');
        $datas['province']=I('post.province');
        $datas['city']=I('post.city');
        $datas['twon']=I('post.twon');
        $datas['address']=I('post.address');
        $datas['is_default']=I('post.is_default');
        $datas['mobile']=I('post.mobile');
        $datas['zipcode']=I('post.zipcode');
            $logic = new UsersLogic();
            $data = $logic->add_address($datas['user_id'],0,$datas);
        $this->ajaxReturn($data);

    }

    /*
     * 获取编辑地址
     */
    public function edit_address(){
        header("Content-type:text/html;charset=utf-8");
        $token=I('get.token');
        if($token !=self::CHECK_TOKEN){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
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
        $token=I('post.token');
        if($token !=self::CHECK_TOKEN){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        $id = I('post.address_id/d');
        $uid=I('post.user_id');
        $users= M('user_address')->where(array('address_id'=>$id,'user_id'=> $uid))->find();
        I('post.user_id')?$data['user_id']=I('post.user_id'):$users['user_id'];
        I('post.consignee')?$data['consignee']=I('post.consignee'):$users['consignee'];
        I('post.province')?$data['province']=I('post.province'):$users['province'];
        I('post.city')?$data['city']=I('post.city'):$users['city'];
        I('post.address')?$data['address']=I('post.address'):$users['address'];
        I('post.twon')?$data['twon']=I('post.twon'):$users['twon'];
        I('post.is_default')?$data['is_default']=I('post.is_default'):$users['is_default'];
        I('post.mobile')?$data['mobile']=I('post.mobile'):$users['mobile'];
        $logic = new UsersLogic();
        $data = $logic->add_address($uid,$id,$data);
        $this->ajaxReturn($data);
    }
    /*
     * 设置默认收货地址
     */
    public function set_default(){
        $token=I('get.token');
        if($token !=self::CHECK_TOKEN){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
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
        $token=I('get.token');
        if($token !=self::CHECK_TOKEN){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
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


    public function save_pickup()
    {
        $post = I('post.');
        if (empty($post['consignee'])) {
            return array('status' => -1, 'msg' => '收货人不能为空', 'result' => '');
        }
        if (!$post['province'] || !$post['city'] || !$post['district']) {
            return array('status' => -1, 'msg' => '所在地区不能为空', 'result' => '');
        }
        if(!check_mobile($post['mobile'])){
            return array('status'=>-1,'msg'=>'手机号码格式有误','result'=>'');
        }
        if(!$post['pickup_id']){
            return array('status'=>-1,'msg'=>'请选择自提点','result'=>'');
        }

        $user_logic = new UsersLogic();
        $res = $user_logic->add_pick_up($this->user_id, $post);
        if($res['status'] != 1){
            exit('<script>alert("'.$res['msg'].'");history.go(-1);</script>');
        }
        $call_back = $_REQUEST['call_back'];
        echo "<script>parent.{$call_back}({$post['province']},{$post['city']},{$post['district']});</script>";
        exit(); // 成功 回调closeWindow方法 并返回新增的id
    }

    /*
     * 获取（编辑）个人信息
     */
    public function getinfo(){
        $uid=I('post.user_id');
        $userLogic = new UsersLogic();
        $user_info = $userLogic->get_info($uid); // 获取用户信息
        $yq=tpCache('basic.tax');
        $count=M('users')->where(['first_leader'=>$uid])->order('reg_time desc')->field('reg_time,user_id,nickname')->count();
        if($count==0){
            $user_info['user_level']='环保大使V0';
        }else{
            $level=ceil($count/$yq);
            $user_info['user_level']='环保大使V'.$level;
        }
        if($user_info){
            return json_encode(array('status'=>1,'data'=>$user_info,'msg'=>'获取成功'));
        }else{
            return json_encode(array('status'=>0,'data'=>'','msg'=>'获取失败'));
        }
    }
//保存修改信息
    public function setinfo()
    {
        $token=I('post.token');
        $uid=I('post.user_id');
        if($token !=self::CHECK_TOKEN){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        if(empty($uid)){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'参数错误'));
        }
        $users=M('users')->where(['user_id'=>$uid])->find();
        $userLogic = new UsersLogic();
        I('post.nickname')?$data['nickname']=I('post.nickname'):$users['nickname'];
        I('post.sex')?$data['sex']=I('post.sex'):$users['sex'];
        I('post.qq')?$data['qq']=I('post.qq'):$users['qq'];
        I('post.email')?$data['email']=I('post.email'):$users['email'];
        I('post.province')?$data['province']=I('post.province'):$users['province'];
        I('post.twon')?$data['twon']=I('post.twon'):$users['twon'];
        I('post.city')?$data['city']=I('post.city'):$users['city'];
        I('post.address')?$data['address']=I('post.address'):$users['address'];
        $file = request()->file('head_pic');
        $info=$this->upload($file);
        $info?$data['head_pic']=$info:$users['head_pic'];

        if (!empty($data['email'])) {
            $c = M('users')->where(['email' => $data['email'], 'user_id' => ['<>', $uid]])->count();
            if($c>0){
                return json_encode(array('status'=>0,'data'=>'','msg'=>'邮箱已被使用'));
            }
        }
        if (!$userLogic->update_info($uid, $data)){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'修改失败'));
        }else{
            return json_encode(array('status'=>1,'data'=>$info,'msg'=>'修改成功'));
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
     *商品收藏
     */
    public function goods_collect(){
        $token=I('get.token');
        $uid=I('get.user_id');
        if($token !=self::CHECK_TOKEN){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        if(empty($uid)){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'参数错误'));
        }
        $userLogic = new UsersLogic();
        $data = $userLogic->get_goods_collect($uid);
        foreach($data as $k=>$v){
            $data[$k]['original_img']='http://'.$_SERVER['HTTP_HOST'].$v['original_img'];
        }
        if($data){
            return json_encode(array('status'=>1,'data'=>$data,'msg'=>'获取成功'));
        }else{
            return json_encode(array('status'=>0,'data'=>'','msg'=>'暂无收藏'));
        }
    }

    /*
     * 删除收藏商品
     */
    public function del_goods_collect(){
        $uid = I('get.user_id/d');
        $id = I('get.collect_id/d');
        $token=I('get.token');
        if($token !=self::CHECK_TOKEN){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        if(!$id || !$uid){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'缺少ID参数'));
        }
        $map['collect_id']  = array('in',$id);
        $map['user_id'] = array('eq' ,$uid );
        $row = M('goods_collect')->where($map)->delete();
        if(!$row){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'删除失败'));
        }else{
            return json_encode(array('status'=>1,'data'=>'','msg'=>'删除成功'));
        }
    }

    /*
     * 添加一个收藏商品
     */
    public function add_goods_collect(){
        $uid = I('post.user_id/d');
        $id = I('post.goods_id/d');
        $token=I('post.token');
        if($token !=self::CHECK_TOKEN){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        if(!$id || !$uid){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'缺少ID参数'));
        }
        $data['user_id']=$uid;
        $data['goods_id']=$id;
        $data['add_time']=time();
        $res=M('goods_collect')->where(['user_id'=>$uid,'goods_id'=>$id])->count();
        if($res>0){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'已收藏过，无需再次收藏'));
        }
        $row = M('goods_collect')->add($data);
        if(!$row){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'收藏失败'));
        }else{
            return json_encode(array('status'=>1,'data'=>'','msg'=>'收藏成功'));
        }
    }

//忘记密码
    public function forget_pwd()
    {
        $token=I('post.token');
        if($token !=self::CHECK_TOKEN){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
//        $verify_code = I('post.verify');
//        $verify = new Verify();
//        if (!$verify->check($verify_code))
//        {
//            return json_encode(array('status'=>0,'data'=>'','msg'=>'验证码错误'));
//        }
        $uid = I('post.user_id');
        $username = I('post.mobile');
        $password = I('post.pwd');
        $password2 = I('post.pwd2');
        if($password2 != $password){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'两次密码不一致'));
        }
        if (isset($username)) {
        if(empty($uid)){
            $user = M('users')->where('mobile', $username)->find();
        }else{
            $user = M('users')->where('user_id',$uid)->where('mobile', $username)->find();
        }
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
            }else{
            return json_encode(array('status'=>0,'data'=>'','msg'=>'参数错误'));
        }
    }



    public function bind_auth()
    {
 
        $list = M('plugin')->cache(true)->where(array('type' => 'login', 'status' => 1))->select();
        if ($list) {
            foreach ($list as $val) {
                $val['is_bind'] = 0;
                
                $thridUser = M('OauthUsers')->where(array('user_id'=>$this->user['user_id'] , 'oauth'=>$val['code']))->find();
                 if ($thridUser) {
                    $val['is_bind'] = 1;
                }
                $val['bind_url'] = U('LoginApi/login', array('oauth' => $val['code']));
                $val['bind_remove'] = U('User/bind_remove', array('oauth' => $val['code']));;
                $val['config_value'] = unserialize($val['config_value']);
                $lists[] = $val;
            }
        }
        $this->assign('lists', $lists);
        return $this->fetch();
    }

    public function bind_remove()
    {
        $oauth = I('oauth'); 
        $row = M('OauthUsers')->where(array('user_id' => $this->user_id , 'oauth'=>$oauth))->delete();
        if ($row) {
            $this->success('解除绑定成功', U('Home/User/bind_auth'));
        } else {
            $this->error('解除绑定失败', U('Home/User/bind_auth'));
        }
        
    }
    public function check_captcha(){
    	$verify = new Verify();
    	$type = I('post.type','user_login');
    	if (!$verify->check(I('post.verify_code'), $type)) {
    		exit(json_encode(0));
    	}else{
    		exit(json_encode(1));
    	}
    }
    
    public function check_username(){
    	$username = I('post.username');
    	if(!empty($username)){
    		$count = M('users')->where("email", $username)->whereOr('mobile', $username)->count();
    		exit(json_encode(intval($count)));
    	}else{
    		exit(json_encode(0));
    	}  	
    }

    public function identity()
    {
        if ($this->user_id > 0) {
            header("Location: " . U('Home/User/Index'));
        }
        $user = session('find_password');
        if (empty($user)) {
            $this->error("请先验证用户名", U('User/forget_pwd'));
        }
        $this->assign('userinfo', $user);
        return $this->fetch();
    }
      
    /**
     * 验证码验证
     * $id 验证码标示
     */
    private function verifyHandle($id)
    {
        $verify = new Verify();
        $result = $verify->check(I('post.verify_code'), $id ? $id : 'user_login');
        if (!$result) {
            return false;
        }else{
            return true;
        }
    }

    //生成验证码
    public function verify(){
        $Verify = new Verify();
        $Verify->fontSize = 17;
        $Verify->length   = 4;
        $Verify->codeSet = '0123456789';
        $Verify->entry();
    }

    /**
     * 安全设置
     */
    public function safety_settings()
    {
        $userLogic = new UsersLogic();
        $user_info = $userLogic->get_info($this->user_id); // 获取用户信息
        $user_info = $user_info['result'];        
        $this->assign('user',$user_info);
        return $this->fetch();
    }
    
    /**
     * 申请提现记录
     */
    public function withdrawals(){
    	if(IS_POST)
    	{
            if(!$this->verifyHandle('withdrawals')){
                $this->ajaxReturn(['status'=>0,'msg'=>'图像验证码错误']);
            };
    		$data = I('post.');
    		$data['user_id'] = $this->user_id;    		    		
    		$data['create_time'] = time();                
                $distribut_min = tpCache('basic.min'); // 最少提现额度
                if($data['money'] < $distribut_min)
                {
                    $this->ajaxReturn(['status'=>0,'msg'=>'每次最少提现额度'.$distribut_min]);
                        exit;
                }
                if($data['money'] > $this->user['user_money'])
                {
                    $this->ajaxReturn(['status'=>0,'msg'=>"你最多可提现{$this->user['user_money']}账户余额."]);
                        exit;
                }
            if(encrypt($data['paypwd']) != $this->user['paypwd']){
                $this->ajaxReturn(['status'=>0,'msg'=>"支付密码错误"]);
            }

    		if(M('withdrawals')->add($data)){
                $this->ajaxReturn(['status'=>1,'msg'=>"已提交申请",'url'=>U('User/recharge',['type'=>2])]);
                exit;
    		}else{
                $this->ajaxReturn(['status'=>1,'msg'=>'提交失败,联系客服!']);
                exit;
    		}
    	}

       /* $Userlogic = new UsersLogic();
        $result= $Userlogic->get_withdrawals_log($this->user_id);  //用户资金变动记录
        $this->assign('show',$result['show']);// 赋值分页输出
        $this->assign('list',$result['result']); // 下线*/
        return $this->fetch();
    }
    
   public  function recharge(){
   		if(IS_POST){
   			$user = session('user');
   			$data['user_id'] = $this->user_id;
   			$data['nickname'] = $user['nickname'];
   			$data['account'] = I('account');
   			$data['order_sn'] = 'recharge'.get_rand_str(10,0,1);
   			$data['ctime'] = time();
   			$order_id = M('recharge')->add($data);
   			if($order_id){
   				$url = U('Payment/getPay',array('pay_radio'=>$_REQUEST['pay_radio'],'order_id'=>$order_id));
                $this->redirect($url);
   			}else{
   				$this->error('提交失败,参数有误!');
   			}
   		}
   		
	   	$paymentList = M('Plugin')->where("`type`='payment' and code!='cod' and status = 1 and  scene in(0,2)")->select();
       print_r($paymentList);die;
	   	$paymentList = convert_arr_key($paymentList, 'code');	   	
	   	foreach($paymentList as $key => $val)
	   	{
	   		$val['config_value'] = unserialize($val['config_value']);
	   		if($val['config_value']['is_bank'] == 2)
	   		{
	   			$bankCodeList[$val['code']] = unserialize($val['bank_code']);
	   		}
	   	}
	   	$bank_img = include APP_PATH.'home/bank.php'; // 银行对应图片
	   	$this->assign('paymentList',$paymentList);
	   	$this->assign('bank_img',$bank_img);
	   	$this->assign('bankCodeList',$bankCodeList);

       $type = I('type');
       $Userlogic = new UsersLogic();
       if($type == 1){
           $result=$Userlogic->get_account_log($this->user_id);  //用户资金变动记录
       }else if($type == 2){
           $result=$Userlogic->get_withdrawals_log($this->user_id);  //提现记录
       }else{
           $result=$Userlogic->get_recharge_log($this->user_id);  //充值记录
       }
        $this->assign('page', $result['show']);
        $this->assign('lists', $result['result']);
   		return $this->fetch();
   } 

    /**
     *  用户消息通知
     * @author dyr
     * @time 2016/09/01
     */
    public function message_notice()
    {
        return $this->fetch('user/message_notice');
    }
    /**
     * ajax用户消息通知请求
     * @author dyr
     * @time 2016/09/01
     */
    public function ajax_message_notice()
    {
        $type = I('type');
        $user_logic = new UsersLogic();
        $message_model = new MessageLogic();
        if ($type == 0) {
            //系统消息
            $user_sys_message = $message_model->getUserMessageNotice();
        } else if ($type == 1) {
            //活动消息：后续开发
            $user_sys_message = array();
        } else {
            //全部消息：后续完善
            $user_sys_message = $message_model->getUserMessageNotice();
        }
        $this->assign('messages', $user_sys_message);
        return $this->fetch('user/ajax_message_notice');
    }

    /**
     * ajax用户消息通知请求
     */
    public function set_message_notice()
    {
        $type = I('type');
        $msg_id = I('msg_id');
        $user_logic = new UsersLogic();
        $res = $user_logic->setMessageForRead($type,$msg_id);
        $this->ajaxReturn($res);
    }

    /**
     * 支付密码
     * @return mixed
     */
    public function paypwd()
    {
        //检查是否第三方登录用户
        $logic = new UsersLogic();
        $data = $logic->get_info($this->user_id);
        $user = $data['result'];
        if(strrchr($_SERVER['HTTP_REFERER'],'/') =='/cart2.html'){  //用户从提交订单页来的，后面设置完有要返回去
            session('payPriorUrl',U('Mobile/Cart/cart2'));
        }
        if ($user['mobile'] == '')
            $this->error('请先绑定手机', U('User/userinfo',['action'=>'mobile']));
        $step = I('step', 1);
        if ($step > 1) {
            $check = session('validate_code');
            if (empty($check)) {
                $this->error('验证码还未验证通过', U('Home/User/paypwd'));
            }
        }
        if (IS_POST && $step == 3) {
            $userLogic = new UsersLogic();
            $data = I('post.');
            $data = $userLogic->paypwd($this->user_id, I('new_password'), I('confirm_password'));
            if ($data['status'] == -1)
                $this->error($data['msg']);
            //$this->success($data['msg']);
            $this->redirect(U('Home/User/paypwd', array('step' => 3)));
            exit;
        }
        $this->assign('step', $step);
        return $this->fetch();
    }

    /**
     *  点赞
     * @author lxl
     * @time  17-4-20
     * 拷多商家Order控制器
     */
    public function ajaxZan()
    {
        $comment_id = I('post.comment_id/d');
        $user_id = $this->user_id;
        $comment_info = M('comment')->where(array('comment_id' => $comment_id))->find();  //获取点赞用户ID
        $comment_user_id_array = explode(',', $comment_info['zan_userid']);
        if (in_array($user_id, $comment_user_id_array)) {  //判断用户有没点赞过
            $result['success'] = 0;
        } else {
            array_push($comment_user_id_array, $user_id);  //加入用户ID
            $comment_user_id_string = implode(',', $comment_user_id_array);
            $comment_data['zan_num'] = $comment_info['zan_num'] + 1;  //点赞数量加1
            $comment_data['zan_userid'] = $comment_user_id_string;
            M('comment')->where(array('comment_id' => $comment_id))->save($comment_data);
            $result['success'] = 1;
        }
        return json_encode($result);
    }

    /**
     * 删除足迹
     * @author lxl
     * 拷多商家User控制器
     */
    public function del_visit_log(){
        $token=I('get.token');
        if($token !=self::CHECK_TOKEN){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        $visit_id = I('visit_id/d' , 0);
        $uid = I('user_id/d' , 0);
        if(empty($visit_id) || empty($uid)){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'参数无效'));
        }
        $map['visit_id']  = array('in',$visit_id);
        $map['user_id'] = array('eq' ,$uid );
        $row = M('goods_visit')->where($map)->delete();
        if($row){
            $this->ajaxReturn(['status'=>1 , 'msg'=> '删除成功','data'=>'']);
        }else{
            $this->ajaxReturn(['status'=>0 , 'msg'=> '删除失败','data'=>'']);
        }
    }

    /**
     * 我的足迹
     * @author lxl
     * 拷多商家User控制器
     * */
    public function visit_log()
    {
        $token=I('post.token');
        if($token !=self::CHECK_TOKEN){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        $map['user_id'] = I('post.user_id');
        if(empty($map['user_id'])){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'参数无效'));
        }
        $visit_list = M('goods_visit a')->field("a.*,g.goods_name,g.shop_price,g.store_count,g.original_img,g.sales_sum")
            ->join('__GOODS__ g', 'a.goods_id = g.goods_id', 'LEFT')
            ->where($map)
            ->order('a.visittime desc')
            ->select();
        foreach ($visit_list as $key => $value) {
            $visit_list[$key]['original_img']='http://'.$_SERVER['HTTP_HOST'].$value['original_img'];
        }
        $visit_log = array();
        if ($visit_list) {
            $now = time();
            $endLastweek = mktime(23, 59, 59, date('m'), date('d') - date('w') + 7 - 7, date('Y'));
            $weekarray = array("日", "一", "二", "三", "四", "五", "六");
            foreach ($visit_list as $k => $val) {
                if ($now - $val['visittime'] < 3600 * 24 * 7) {
                    if (date('Y-m-d') == date('Y-m-d', $val['visittime'])) {
                        $val['date'] = 'Day';
                    } else {
                        if ($val['visittime'] < $endLastweek) {
                            $val['date'] = "UpWeek" . $weekarray[date("w", $val['visittime'])];
                        } else {
                            $val['date'] = "ThisWeek" . $weekarray[date("w", $val['visittime'])];
                        }
                    }
                } else {
                    $val['date'] = 'earlier';
                }
                $cat_ids[] = $val['cat_id'];
                $visit_log[$val['date']][] = $val;
            }
            return json_encode(array('status'=>1,'data'=>$visit_log,'msg'=>'获取成功'));
        }else{
            return json_encode(array('status'=>0,'data'=>'','msg'=>'暂无足迹'));
        }

    }
//省
    public function getaddress(){
        $province = M('region2')->where ( array('parent_id'=>0) )->select ();
//        $this->assign('province',$province);
//        return $this->fetch();
        if($province){
            return json_encode(array('status'=>1,'data'=>$province,'msg'=>'获取成功'));
        }else{
            return json_encode(array('status'=>0,'data'=>'','msg'=>'获取失败'));
        }

    }
    //市县
    public function getRegion(){
        $Region=M("region2");
        $map['parent_id']=$_REQUEST["pid"];
        $map['level']=$_REQUEST["type"];
        $list=$Region->where($map)->select();
        echo json_encode($list);
    }
//    获取定位
public function ipEare(){
    $ip = Request::instance()->ip();
    $location = new IpLocation();
    $address = $location->getlocation($ip);
    if($address){
        return json_encode(array('status'=>1,'msg'=>'定位成功','data'=>$address));
    }else{
        return json_encode(array('status'=>0,'msg'=>'定位失败','data'=>''));
    }

}


    /**
     * 会员签到积分奖励
     */
    public function sign() {
        $config = tpCache('basic');
        $integral = $config['sign_integral'];
        $msg = "签到赠送" . $integral . "积分";
        $user_id = I('user_id');
        $date=strtotime(date('Y-m-d 0:0:0',strtotime(I('date'))));
        if(!$user_id){
            $this->ajaxReturn(['status' => 0, 'msg' => '参数错误！', 'result' => '']);
        }
        if(!isset($data) || empty($data)){
            $data=Db::name('user_sign')->where(['user_id' => $user_id])->find();
            $result['integral']=$integral;

            if($result['signtotal']<1){
                $result['signtotal']=0;
                $result['signcount']=0;
                $result['thismonth']=0;
            }else{
                $result['signtotal']=$data['signtotal'];
                $result['signcount']=$data['signcount'];
                $result['thismonth']=$data['thismonth'];
            }
            $status = ['status' => 1, 'msg' => '获取成功成功！', 'result' => $result];
            $this->ajaxReturn($status);
        }
            //签到开关
            if ($config['sign_on_off'] > 0) {
                $map['lastsign'] = $date;
                $map['user_id'] = $user_id;
                $check = DB::name('user_sign')->where($map)->find();
                $data=Db::name('user_sign')->where(['user_id' => $user_id])->find();
                $result['integral']=$integral;
                $result['signtotal']=$data['signtotal'];
                $result['signcount']=$data['signcount'];
                $result['thismonth']=$data['thismonth'];
                $check && $this->ajaxReturn(['status' => 0, 'msg' => '您今天已经签过啦！', 'result' =>$result]);
                if (!DB::name('user_sign')->where(['user_id' => $user_id])->find()) {
                    if(!$date){
                        $result['integral']=$integral;
                        $result['signtotal']=0;
                        $result['signcount']=0;
                        $result['thismonth']=0;
                        $this->ajaxReturn(['status' => 0, 'msg' => '111', 'result' =>$result]);
                    }
                    //第一次签到
                    $data = [];
                    $data['user_id'] = $user_id;
                    $data['signtotal'] = 1;
                    $data['lastsign'] = $date;
                    $data['cumtrapz'] = $config['sign_integral'];
                    $data['signtime'] = "$date";
                    $data['signcount'] = 1;
                    $data['thismonth'] = $config['sign_integral'];
                    if (M('user_sign')->add($data)) {
                        $data=Db::name('user_sign')->where(['user_id' => $user_id])->find();
                        $result['integral']=$integral;
                        $result['signtotal']=$data['signtotal'];
                        $result['signcount']=$data['signcount'];
                        $result['thismonth']=$data['thismonth'];
                        $status = ['status' => 1, 'msg' => '签到成功！', 'result' => $result];
                    } else {
                        $status = ['status' => 0, 'msg' => '签到失败!', 'result' => ''];
                    }
                    $this->ajaxReturn($status);
                } else {
                    if(!$date){
                        $result['integral']=$integral;
                        $result['signtotal']=0;
                        $result['signcount']=0;
                        $result['thismonth']=0;
                        $this->ajaxReturn(['status' => 0, 'msg' => '111', 'result' =>$result]);
                    }
                    $update_data = array(
                        'signtotal' => ['exp', 'signtotal+' . 1], //累计签到天数
                        'lastsign' => ['exp', "'$date'"], //最后签到时间
                        'cumtrapz' => ['exp', 'cumtrapz+' . $config['sign_integral']], //累计签到获取积分
                        'signtime' => ['exp', "CONCAT_WS(',',signtime ,'$date')"], //历史签到记录
                        'signcount' => ['exp', 'signcount+' . 1], //连续签到天数
                        'thismonth' => ['exp', 'thismonth+' . $config['sign_integral']], //本月累计积分
                    );

                    $daya = Db::name('user_sign')->where('user_id', $user_id)->value('lastsign');    //上次签到时间
                    $dayb = date("Y-n-j", strtotime($date) - 86400);                                   //今天签到时间
                    //不是连续签
                    if ($daya != $dayb) {
                        $update_data['signcount'] = ['exp', 1];                                       //连续签到天数
                    }
                    $mb = date("m", strtotime($date));                                               //获取本次签到月份
                    //不是本月签到
                    if (intval($mb) != intval(date("m", strtotime($daya)))) {
                        $update_data['signcount'] = ['exp', 1];                                      //连续签到天数
                        $update_data['signtime'] = ['exp', "'$date'"];                                  //历史签到记录;
                        $update_data['thismonth'] = ['exp', $config['sign_integral']];              //本月累计积分
                    }

                    $update = Db::name('user_sign')->where(['user_id' => $user_id])->update($update_data);

                    (!$update) && $this->ajaxReturn(['status' => 0, 'msg' => '网络异常！', 'result' => '']);

                    $signcount = Db::name('user_sign')->where('user_id', $user_id)->value('signcount');
                    $integral = $config['sign_integral'];
                    //满足额外奖励
                    if (( $signcount >= $config['sign_signcount']) && ($config['sign_on_off'] > 0)) {
                        Db::name('user_sign')->where(['user_id' => $user_id])->update([
                            'cumtrapz' => ['exp', 'cumtrapz+' . $config['sign_award']],
                            'thismonth' => ['exp', 'thismonth+' . $config['sign_award']]
                        ]);
                        $integral = $config['sign_integral'] + $config['sign_award'];
                        $msg = "签到赠送" . $config['sign_integral'] . "积分，连续签到奖励" . $config['sign_award'] . "积分，共" . $integral . "积分";
                    }
                }
                if ($config['sign_integral'] > 0 && $config['sign_on_off'] > 0) {
                    accountLog($user_id, 0, $integral, $msg);
                    $data=Db::name('user_sign')->where(['user_id' => $user_id])->find();
                    $result['integral']=$integral;
                    $result['signtotal']=$data['signtotal'];
                    $result['signcount']=$data['signcount'];
                    $result['thismonth']=$data['thismonth'];
                    $status = ['status' => 1, 'msg' => '签到成功！', 'result' => $result];
                } else {
                    $status = ['status' => 0, 'msg' => '签到失败!', 'result' => ''];
                }
                $this->ajaxReturn($status);
            } else {
                $this->ajaxReturn(['status' => 0, 'msg' => '该功能未开启！', 'result' => '']);
            }
    }
//    会员积分记录
    public function points_log()
    {
        $uid=I('user_id');
        $data=M('account_log')->where(['mobile'=>$uid])->field('pay_points,change_time,desc')->order('change_time desc')->select();
        foreach($data as $k=>$v){
            $data[$k]['change_time']=date('Y-m-d',$v['change_time']);
        }
        if($data){
            $this->ajaxReturn(['status' => 1, 'msg' => '获取成功', 'data' => $data]);
        }else{
            $this->ajaxReturn(['status' => 0, 'msg' => '暂无积分变动记录', 'data' => '']);
        }
    }
    //会员等级
    public function userLevel(){
        $token=I('token');
        if($token !=self::CHECK_TOKEN){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        $data=M('user_level')->field('level_name,amount,level')->select();
        foreach($data as $k=>$v){
            $data[$k]['level']=$v['level']*10;
            $data[$k]['title']='用户积分达到'.$v['amount'].'享受购物'.$v['level']*'10'.'折优惠';
            $data[$k]['content']='享受购物'.$v['level']*'10'.'折优惠';
        }
      if($data){
          return json_encode(array('status'=>1,'msg'=>'获取成功','data'=>$data));
      }else{
          return json_encode(array('status'=>0,'msg'=>'获取失败','data'=>''));
      }

    }
//申请提现
    public function user_tx(){
        $token=I('token');
        if($token !=self::CHECK_TOKEN){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        $tpCatch=tpCache('basic');
        if($tpCatch['withdrawal_off']==1){
            return json_encode(array('status'=>0,'msg'=>'提现功能暂未开通，敬请期待','data'=>''));
        }
        $data['user_id']=I('user_id');
        $data['realname']=I('name');
        $data['bank_card']=I('bankno');
        $data['create_time']=time();
        $data['money']=I('money');
        $tpCatch=tpCache('basic');
        if($data['money']<$tpCatch['min']){
            return json_encode(array('status'=>0,'msg'=>'最少提现'.$tpCatch['min'].'元','data'=>''));
        }
        $mon=M('user')->where(['user_id'=>$data['user_id']])->getField('user_money');
        if($mon<$tpCatch['need']){
            return json_encode(array('status'=>0,'msg'=>'余额达到'.$tpCatch['min'].'元可提现','data'=>''));
        }
        $data['remark']='申请提现'.$data['money'];
        $data['status']=0;
        $data['pay_code']=uniqid('tx',12);
        $res=M('withdrawals')->add($data);
        if($res){
            $this->ajaxReturn(['status' => 1, 'msg' => '申请成功', 'data' => '']);
        }else{
            $this->ajaxReturn(['status' => 0, 'msg' => '申请失败', 'data' => '']);
        }
    }
    //申请发票
    public function ToApplyTheInvoice(){
        $token=I('token');
        if($token !=self::CHECK_TOKEN){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        $data['user_id']=I('user_id');
        $data['invoice_title']=I('name');
        $data['phone']=I('phone');
        $data['address']=I('address');
        $data['order_id']=I('order_sn');
        $data['invoice_desc']=I('content');
        $data['atime']=time();
        $data['status']=0;
        $order=M('order')->where(['order_sn'=>$data['order_id'],'user_id'=>$data['user_id']])->field('order_status')->find();
        if(count($order)<1){
            return json_encode(array('status'=>0,'msg'=>'单号错误，请检查！','data'=>''));
        }
        if($order['order_status']==0 || $order['order_status']==1){
            return json_encode(array('status'=>0,'msg'=>'该订单已取消或还未付款','data'=>''));
        }
        $invoice=M('invoice')->where(['order_id'=>$data['order_id']])->find();
        if($invoice){
            return json_encode(array('status'=>0,'msg'=>'该订单已申请过发票了！','data'=>''));
        }
        $res=M('invoice')->add($data);
        if($res){
            return json_encode(array('status'=>1,'msg'=>'申请成功！','data'=>''));
        }else{
            return json_encode(array('status'=>0,'msg'=>'申请失败！','data'=>''));
        }
    }

//    代理条件等
     public function daili(){
         $data=tpCache('daili');
         return json_encode(array('status'=>1,'msg'=>'获取成功','data'=>$data));
     }

}
