<?php
namespace app\ppb\controller;

use think\Controller;
use app\common\logic\OrderLogic;
class Index extends Base{

    public function index(){
        //首页banner
        $ad=M('ad')->where(['pid'=>2,'enabled'=>1])->field('ad_id,ad_link,ad_code')->limit(3)->select();
        foreach($ad as $k=>$v){
            $ad[$k]['ad_code']=SITE_URL.$v['ad_code'];
        }
        //首页自定义导航栏
        $nav=M('navigation')->where(['is_show'=>1])->order('sort asc')->field('id,name,img')->limit(4)->select();
        foreach($nav as $k=>$v){
            $nav[$k]['img']=SITE_URL.$v['img'];
        }
        //分类
        $data=M('goods_category2')->where(['is_show'=>1])->field('id,mobile_name,parent_id,image')->select();
        $cate=$this->list_to_tree($data, 'id','parent_id',$child = 'child',$root=0);
        $data=['banner'=>$ad,'nav'=>$nav,'category'=>$cate];
        return json_encode(array('status' => 1,'data'=>$data ,'msg'=>'获取成功'));
    }
    //首页资讯、常见问题
    public function Information(){
        header("Access-Control-Allow-Origin: *");
        $page=I('page/d',1);
        $type=I('type/d',1);  //type=1  首页资讯   ； type=2   常见问题
        if($type==1){
            $model=M('article');
        }elseif($type==2){
            $model=M('article2');
        }
        $data2=$model->where(['is_open'=>1])->field('article_id,title,keywords,content,add_time,click,thumb')->order('add_time desc')->limit((($page-1)*6),($page*6))->select();
        $count=$model->where(['is_open'=>1])->count();
        $countPage=ceil($count/6);
        foreach($data2 as $k=>$v){
            $data2[$k]['add_time']=date('Y-m-d H:i',$v['add_time']);
            $data2[$k]['content']=html_entity_decode($v['content']);
            $data2[$k]['thumb']=SITE_URL.$v['thumb'];
        }
        $data1['data']=$data2;
        $data1['count']=$count;
        $data1['countPage']=$countPage;
        return json_encode(array('status' => 1,'data'=>$data1 ,'msg'=>'获取成功'));
    }
        /**
     * 前端发送短信方法: APP/WAP/PC 共用发送方法
     */
    public function sendSms(){
        $scene=I('sence',1);
        $mobile=I('mobile');
        $this->send_validate_code($scene,$mobile);
    }
    public function send_validate_code($scene,$mobile){
        // $scene   发送使用场景,1注册，4下单
        //$code    验证码时为数字，通知是商城名称
        $session_id = I('unique_id' , session_id());
        //判断是否存在验证码
        $data = M('sms_log')->where(array('mobile'=>$mobile,'session_id'=>$session_id, 'status'=>1))->order('id DESC')->find();
        //获取时间配置
        $sms_time_out = tpCache('sms.sms_time_out');
//            $sms_time_out = 10;
        $sms_time_out = $sms_time_out ? $sms_time_out : 120;
        //120秒以内不可重复发送
        if($data && (time() - $data['add_time']) < $sms_time_out){
            $return_arr = array('status'=>-1,'msg'=>$sms_time_out.'秒内不允许重复发送');
            ajaxReturn($return_arr);
        }
        if($scene==1){
            $code=rand(1000,9999);
            $templateCode='SMS_138072655';
        }elseif($scene==4){
            $code=tpCache('shop_info.store_name');
            $templateCode='SMS_138525095';
        }
        //发送短信
        $resp = sendSms($scene , $mobile , $code, $session_id,$templateCode);

        if($resp['status'] == 1){
            //发送成功, 修改发送状态位成功
            M('sms_log')->where(array('mobile'=>$mobile,'code'=>$code,'session_id'=>$session_id , 'status' => 0))->save(array('status' => 1));
            $return_arr = array('status'=>1,'msg'=>'发送成功,请注意查收');
        }else{
            $return_arr = array('status'=>-1,'msg'=>'发送失败'.$resp['msg']);
        }
        ajaxReturn($return_arr);
    }

    //资讯详情
    public function InformationDetail(){
        header("Access-Control-Allow-Origin: *");
        $id=I('get.article_id/d',1);
        $data=M('article')->where(['article_id'=>$id])->field('article_id,title,content,add_time,click,thumb')->find();
        $data['add_time']=date('Y-m-d H:i',$data['add_time']);
        $data['content']=html_entity_decode($data['content']);
        $data['thumb']=SITE_URL.$data['thumb'];
        return json_encode(array('status' => 1,'data'=>$data ,'msg'=>'获取成功'));
    }
    //服务城市
    public function ktCity(){
        //服务范围图片
        $ad=M('ad')->where(['pid'=>3,'enabled'=>1])->field('ad_id,ad_link,ad_code')->find();
        $ad['ad_code']=SITE_URL.$ad['ad_code'];
        //已开通范围的内容
        $province=M('ktcity')->select();
        $meun=$this->list_to_tree($province,'id','parent_id','child',0);
        foreach ($meun as $k => $v) {
            if(!isset($meun[$k]['child'])){
                $meun[$k]['child']=[array('name'=>'')];
            }
        }
        // print_r($meun);die;
        $data=['ad'=>$ad,'meun'=>$meun];
        if($data){
            return json_encode(array('status'=>1,'msg'=>'获取成功','data'=>$data));
        }else{
            return json_encode(array('status'=>0,'msg'=>'暂无','data'=>''));
        }
    }
    public function list_to_tree($list, $pk='id',$pid = 'pid',$child = '_child',$root=0) {
        // 创建Tree
        $tree = array();
        if(is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId = $data[$pid];
                if ($root == $parentId) {
                    $tree[] =& $list[$key];
                }else{
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }
//服务介绍(中心)
    public function serviceIsIntroduced(){
        $type=I('type',1);//所属页面：1服务介绍，2服务中心 ，3关于我们  ，4用户协议
        if($type==1){ //服务介绍
            $data=M('article_cat_2')->where(['show_in_nav'=>0,'cat_id'=>99])->field('cat_name,img,content')->find();
        }elseif($type==2){  //服务中心
            $data=M('article_cat_2')->where(['show_in_nav'=>0,'cat_id'=>100])->field('cat_name,img,content')->find();
        }elseif($type==3){  //关于我们
            $data=M('article_cat_2')->where(['show_in_nav'=>0,'cat_id'=>101])->field('cat_name,img,content')->find();
        }elseif($type==4){   //用户协议
            $data=M('article_cat_2')->where(['show_in_nav'=>0,'cat_id'=>102])->field('cat_name,img,content')->find();
           
        }
        if($data){
             $data['img']=SITE_URL.$data['img'];
            $data['content']=html_entity_decode($data['content']);
            return json_encode(array('status'=>1,'msg'=>'获取成功','data'=>$data));
        }else{
            return json_encode(array('status'=>0,'msg'=>'暂无数据','data'=>''));
        }
        
    }
    //意见反馈
    public function setOpinion(){
        $data['user_id']=I('post.user_id');
        if(!$data['user_id']){
            return json_encode(array('status'=>0,'msg'=>'请先登录','data'=>''));
        }
        $data['user_name']=M('users')->where(['user_id'=>I('post.user_id')])->getField('nickname');
        $data['mobile']=I('post.mobile');
        $data['msg_content']=I('post.content');
        $data['msg_time']=time();
        $res=M('feedback')->add($data);
        if($res){
            return json_encode(array('status'=>1,'msg'=>'反馈成功','data'=>''));
        }else{
            return json_encode(array('status'=>0,'msg'=>'反馈失败','data'=>''));
        }
    }
    //推荐有奖
    public function recommended(){
        $data=M('article_cat_2')->where(['show_in_nav'=>0,'cat_id'=>103])->field('cat_name,img,content')->find();
        $data['img']=SITE_URL.$data['img'];
        $data['content']=html_entity_decode($data['content']);
        $image=M('goods_attribute')->where(['attr_status'=>1])->field('attr_id,attr_name,attr_image')->order('order_desc')->limit(4)->select();
        foreach($image as $k=>$v){
            $image[$k]['attr_image']=SITE_URL.$v['attr_image'];
        }
            $data['image']=$image;
        return json_encode(array('status'=>1,'msg'=>'SUCCESS','data'=>$data));
    }
    //保养护理点击获取分类及默认内容
    public function maintenanceClick(){
        $id=I('id',0);   //一级分类id
        if(!$id){
            return json_encode(array('status'=>0,'msg'=>'参数错误','data'=>''));
        }
        $page=I('page',1);
        $cat=M('goods_category')->where(['parent_id'=>$id,'is_show'=>1])->field('id,mobile_name')->order('id desc')->limit(4)->select();
        $goods=M('goods')->where(['cat_id'=>$id,'extend_cat_id'=>$cat[0]['id']])->order('goods_id desc')->field('goods_id,goods_name,goods_remark,shop_price,original_img')->limit(($page-1)*6,$page*6)->select();
        foreach($goods as $k=>$v){
            $goods[$k]['orginal_img']=SITE_URL.$v['orginal_img'];
        }
        $count=M('goods')->where(['cat_id'=>$id,'extend_cat_id'=>$cat[0]['id']])->count();
        $countPage=ceil($count/6);
        $data=['category'=>$cat,'goods'=>$goods,'countPage'=>$countPage];
        return json_encode(array('status'=>1,'msg'=>'SUCCESS','data'=>$data));
    }
    //保养护理点击分类获取下属内容
    public function cateClick(){
        $id=I('id',0); //一级分类id
        $cat_id=I('cat_id',0); //二级分类id
        $page=I('page',1);
        $goods=M('goods')->where(['cat_id'=>$id,'extend_cat_id'=>$cat_id])->order('goods_id desc')->field('goods_id,goods_name,goods_remark,shop_price,original_img')->limit(($page-1)*6,$page*6)->select();
        foreach($goods as $k=>$v){
            $goods[$k]['orginal_img']=SITE_URL.$v['orginal_img'];
        }
        $count=M('goods')->where(['cat_id'=>$id,'extend_cat_id'=>$cat_id])->count();
        $countPage=ceil($count/6);
        $data=['goods'=>$goods,'countPage'=>$countPage];
        return json_encode(array('status'=>1,'msg'=>'SUCCESS','data'=>$data));
    }
    //清洁翻新点击获取分类及默认内容
    public function cleanClick(){
        $id=I('id',0);
        if(!$id){
            return json_encode(array('status'=>0,'msg'=>'参数错误','data'=>''));
        }
        $page=I('page',1);
        $cat=M('goods_category2')->where(['parent_id'=>$id,'is_show'=>1])->field('id,mobile_name')->order('id desc')->select();
        $goods=M('goods')->where(['cat_id'=>$id,'extend_cat_id'=>$cat[0]['id']])->order('goods_id desc')->field('goods_id,goods_name,goods_remark,shop_price,original_img')->limit(($page-1)*6,$page*6)->select();
        foreach($goods as $k=>$v){
            $goods[$k]['orginal_img']=SITE_URL.$v['orginal_img'];
        }
        $count=M('goods')->where(['cat_id'=>$id,'extend_cat_id'=>$cat[0]['id']])->count();
        $countPage=ceil($count/6);
        $data=['category'=>$cat,'goods'=>$goods,'countPage'=>$countPage];
        return json_encode(array('status'=>1,'msg'=>'SUCCESS','data'=>$data));
    }
    //清洁翻新点击分类获取下属内容
    public function cleanCateClick(){
        $id=I('id',0); //一级分类id
        $cat_id=I('cat_id',0); //二级分类id
        $page=I('page',1);
        $goods=M('goods')->where(['cat_id'=>$id,'extend_cat_id'=>$cat_id])->order('goods_id desc')->field('goods_id,goods_name,goods_remark,shop_price,original_img')->limit(($page-1)*6,$page*6)->select();
        foreach($goods as $k=>$v){
            $goods[$k]['orginal_img']=SITE_URL.$v['orginal_img'];
        }
        $count=M('goods')->where(['cat_id'=>$id,'extend_cat_id'=>$cat_id])->count();
        $countPage=ceil($count/6);
        $data=['goods'=>$goods,'countPage'=>$countPage];
        return json_encode(array('status'=>1,'msg'=>'SUCCESS','data'=>$data));
    }
        //样例详情
    public function goodsDetail(){
        $gid=I('goods_id');
        if(!$gid){
            return json_encode(array('status'=>0,'msg'=>'参数错误'));
        }
        $goods=M('goods')->where(['goods_id'=>$gid])->field('goods_id,goods_name,goods_remark,original_img,goods_content')->find();
        if($goods){
            $goods['goods_content']=html_entity_decode($goods['goods_content']);
            return json_encode(array('status'=>1,'msg'=>'SUCCESS','data'=>$goods));
        }else{
            return json_encode(array('status'=>1,'msg'=>'ERROR','data'=>''));
        }
    }
    //预约下单
    public function bookingOrder(){
        $id1=I('id1/d');
        $id2=I('id2/d');
        $where=" parent_id=0 and is_show=1 ";
        if($id1 || $id2){
            $cat=M('goods_category')->where($where)->field('id,mobile_name')->order('id desc')->select();
        }else{
            $cat=M('goods_category')->where(['parent_id'=>0,'is_show'=>1])->field('id,mobile_name')->order('id desc')->select();
        }
        return json_encode(array('status'=>1,'msg'=>'SUCCESS','data'=>$cat));
    }
    //获取种类
    public function clothingCategories(){
        $cloth=I('id'); //种类id，用以获取所属服饰种类
        if(!$cloth){
            return json_encode(array('status'=>0,'msg'=>'参数错误'));
        }
        $data=M('goods_attr')->where(['cat_id'=>$cloth])->field('goods_attr_id,attr_name')->order('goods_attr_id desc')->select();
        if($data){
            return json_encode(array('status'=>1,'msg'=>'SUCCESS','data'=>$data));
        }else{
            return json_encode(array('status'=>1,'msg'=>'暂无种类','data'=>''));
        }
    }
    //获取该类的品牌
    public function getBrands(){
        $attr_id=I('attr_id');  //服饰id
        if(!$attr_id){
            return json_encode(array('status'=>0,'msg'=>'参数错误'));
        }
        $data=M('brand')->where(['cat_id'=>$attr_id])->order('sort asc')->field('id,name')->select();
        if($data){
            return json_encode(array('status'=>1,'msg'=>'SUCCESS','data'=>$data));
        }else{
            return json_encode(array('status'=>0,'msg'=>'暂无品牌','data'=>''));
        }
    }
    //搜索该服饰类下的品牌
    public function searchBrands(){
        $attr_id=I('attr_id');  //服饰id
        $search=I('search');    //关键字
        if(!$search){
            return json_encode(array('status'=>0,'msg'=>'关键字为空'));
        }
        if(!$attr_id){
            return json_encode(array('status'=>0,'msg'=>'参数错误'));
        }
        $where['name']=array('like','%'.$search.'%');
        $where['cat_id']=array('eq',$attr_id);
        $data=M('brand')->where($where)->order('sort asc')->field('id,name,url')->select();
//        foreach($data as $k=>$v){
//            $data[$k]['logo']=SITE_URL.$v['logo'];
//        }
        if($data){
            return json_encode(array('status'=>1,'msg'=>'SUCCESS','data'=>$data));
        }else{
            return json_encode(array('status'=>0,'msg'=>'未找到符合条件的品牌','data'=>''));
        }
    }
    //保养类型，tab切换
    public function maintenanceTypes(){
        $id=I('cat2_id');
        if(!$id){
           $Types=M('article_cat2')->where(['show_in_nav'=>1])->order('sort_order asc')->field('cat_id,cat_name')->limit(4)->select();
           $content=M('article_cat2')->where(['cat_id'=>$Types[0]['cat_id']])->getField('content');
           $content=html_entity_decode($content);
           $maintenanceTypes=array('type'=>$Types,'content'=>$content);
        }else{
           $maintenanceTypes=M('article_cat2')->where(['cat_id'=>$id])->getField('content');
            $maintenanceTypes=html_entity_decode($maintenanceTypes);
        }
        if($maintenanceTypes){
            return json_encode(array('status'=>1,'msg'=>'SUCCESS','data'=>$maintenanceTypes));
        }else{
            return json_encode(array('status'=>0,'msg'=>'暂无数据','data'=>''));
        }
    }
        //自己邮寄页面
    public function selfMail(){
        $data['shop_address']=tpCache('shop_info.address');
        $data['shop_mobile']=tpCache('shop_info.mobile');
        $data['shop_name']=tpCache('shop_info.contact');
        return json_encode(array('status'=>1,'msg'=>'success','data'=>$data));
    }
    //自己邮寄提交
    public function selfSubmit(){
        $oid=I('order_id');
        $res=M('order')->where(['order_id'=>$oid])->save(array('send_shipping_code'=>I('code'),'send_shipping_name'=>I('name'),'send_shipping_time'=>time(),'type'=>2,'order_status'=>0));
        if($res){
            return json_encode(array('status'=>1,'msg'=>'成功','data'=>$res));
        }else{
            return json_encode(array('status'=>0,'msg'=>'网络错误'));
        }
    }

   //添加我的物品
    public function addMyData(){
        $data['category_id']=I('cat_id');   //物品类型id
        $data['attr_id']=I('attr_id');  //物品具体种类id
        $data['brand_id']=I('brand_id');  //品牌id
        $data['user_id']=I('user_id');  //客户id
        $data['add_time']=time();  //添加时间
        $data['mobile_name']=M('goods_category')->where('id',$data['category_id'])->getField('mobile_name');
        $data['attr_name']=M('goods_attr')->where('goods_attr_id',$data['attr_id'])->getField('attr_name');
        $data['brand_name']=M('brand')->where('id',$data['brand_id'])->getField('name');
        // print_r($data);die;
        $res=M('user_store')->add($data);
        if($res){
            return json_encode(array('status'=>1,'msg'=>'添加成功','data'=>$res));
        }else{
            return json_encode(array('status'=>0,'msg'=>'暂无数据','data'=>''));
        }
    }
        //我的物品列表
    public function myDataList(){
        $uid=I('user_id');
        $data=M('user_store')->where(['user_id'=>$uid])->field('id,mobile_name,attr_name,brand_name')->order('add_time asc')->select();
        return json_encode(array('status'=>1,'msg'=>'获取成功','data'=>$data));
    }
    //删除我的物品
    public function delMyData(){
        $id=I('id');
        if(!$id){
            return json_encode(array('status'=>0,'msg'=>'参数错误','data'=>''));
        }
        $res=M('user_store')->where('id',$id)->delete();
        if($res){
            return json_encode(array('status'=>1,'msg'=>'删除成功','data'=>''));
        }else{
            return json_encode(array('status'=>0,'msg'=>'删除失败','data'=>''));
        }
    }
        //提交页面
    public function submine_page(){
        $uid=I('user_id');
        $data['shop_address']=tpCache('shop_info.address');
        $data['shop_mobile']=tpCache('shop_info.mobile');
        $address=M('user_address')->where(['user_id'=>$uid,'is_default'=>1])->field('address_id,consignee,mobile,province,city,twon,address')->find();
//        if($address){
            $CartLogic = new OrderLogic();
            if($address){
                $data['province']=$CartLogic->getdress($address['province']);
                $data['city']=$CartLogic->getdress($address['city']);
                $data['twon']=$CartLogic->getdress($address['twon']);   //获取默认收货地址
            }
//        }
        return json_encode(array('status'=>1,'msg'=>'success','data'=>$data));
    }
    //提交信息
    public function edit_data(){
        $data['user_id']=I('user_id');  //客户id
        $data['goods_id']=I('goods_id');  //样例id
        $data['goods_num']=I('goods_num');  //物品数量
        $wid=I('wid');//物品id
        $did=I('did');//地址id
        $data['goods_id']=I('goods_id');//保养类型id
        $wpData=M('user_store')->where('id',$wid)->field('category_id,brand_id,attr_id')->find();
        $dzData=M('user_address')->where('address_id',$did)->field('consignee,mobile,province,city,twon,address')->find();
        $data['consignee']=$dzData['consignee'];  //客户姓名
        $data['mobile']=$dzData['mobile'];  //客户电话
        $CartLogic = new OrderLogic();
        $data['province']=$CartLogic->getdress($dzData['province']);  //客户省
        $data['city']=$CartLogic->getdress($dzData['city']);  //客户市
        $data['twon']=$CartLogic->getdress($dzData['twon']);  //客户区县
        $data['address']=$dzData['address'];  //客户详细地址
        $data['cat_id']=$wpData['category_id'];   //物品类型id
        $data['attr_id']=$wpData['attr_id'];  //物品具体种类id
        $data['brand_id']=$wpData['brand_id'];  //品牌id
//        $data['type']=I('type'); //类型id，type=1预约取件；type=2 自己邮寄
        $CartLogic = new OrderLogic();
        $data['order_sn']=$CartLogic->get_order_sn(); //订单号
        $data['add_time']=time(); //添加时间
        $data['order_status']=0; //状态
        $data['deleted']=0;
        $res=M('order')->add($data);
        if($res){
            return json_encode(array('status'=>1,'msg'=>'预约成功','data'=>$res));
        }else{
            return json_encode(array('status'=>0,'msg'=>'网络错误'));
        }
    }
    
    //点击确认已寄出或已被取
    public function submine(){
        $oid=I('order_id');
        $order=M('order')->where(['order_id'=>$oid])->getField('order_status');
        if($order==0){
            $res=M('order')->where(['order_id'=>$oid])->save(['order_status'=>1]);
            if($res){
                return json_encode(array('status'=>1,'msg'=>'已确认'));
            }else{
                return json_encode(array('status'=>0,'msg'=>'请重试'));
            }
        }else{
            return json_encode(array('status'=>0,'msg'=>'不需要在确认了'));
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



}