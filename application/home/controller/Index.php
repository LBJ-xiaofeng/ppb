<?php
namespace app\home\controller;
use think\Page;
use think\Verify;
use think\Controller;
use think\Image;
use think\Db;
use app\common\logic\UsersLogic;
class Index extends Base {
    const CHECK_TOKEN='asDFgtRewq';  //设置验证token
    const PAGE_LENGTH=10;
    function __construct(){
        parent::__construct();
    }
    //首页接口
    public function index(){
        header("Content-type: text/html; charset=utf-8");
        $token=I('get.token');
        if($token !=self::CHECK_TOKEN){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        //banner
        $ad_img=M('ad')->where(['enabled'=>1,'pid'=>2])->field('ad_id,ad_code,ad_link')->order('orderby desc')->limit(8)->select();
        foreach($ad_img as $k=>$v){
            $ad_img[$k]['ad_code']='http://'.$_SERVER['HTTP_HOST'].$v['ad_code'];
        }
        //站点配置
        $config=M('config')->where(['name'=>'store_logo'])->find();
        $config['value']='http://'.$_SERVER['HTTP_HOST'].$config['value'];
        //小导航
        $catenav=M('navigation')->where(['is_show'=>1])->field('id,name,url,img')->order('sort desc')->limit(5)->select();
        foreach($catenav as $k=>$v){
            $catenav[$k]['img']='http://'.$_SERVER['HTTP_HOST'].$v['img'];
        }
        //商品分类
        $cat_path = M('goods_category')->field('id,mobile_name')->select();
        foreach($cat_path as $k=>$v){
            $id=$v['id'];
            $sql = "select a.goods_name,a.goods_id,a.shop_price,a.market_price,a.cat_id,a.original_img,a.sales_sum,b.parent_id_path,b.mobile_name from tp_goods as a left join tp_goods_category as b on a.cat_id=b.id where a.is_hot=1 and a.is_on_sale=1 and a.cat_id=$id order by a.sort limit 6";
            $index_hot_goods = Db::query($sql);//首页热卖商品
            $hot_goods[]=$index_hot_goods;
        }
        foreach($hot_goods as $k=>$v){
            foreach($v as $c=>$d){
                $hot_goods[$k][$c]['original_img']='http://'.$_SERVER['HTTP_HOST'].$d['original_img'];
            }
        }
        foreach($cat_path as $k=>$v){
            $id=$v['id'];
            $sql = "select a.goods_name,a.goods_id,a.shop_price,a.market_price,a.cat_id,a.original_img,a.sales_sum,b.parent_id_path,b.mobile_name from tp_goods as a left join tp_goods_category as b on a.cat_id=b.id where a.is_recommend=1 and a.is_on_sale=1 and a.cat_id=$id order by a.sort limit 6";
            $index_recommend_goods = Db::query($sql);//首页推荐商品
            $recommend_goods[]=$index_recommend_goods;
        }
        foreach($recommend_goods as $k=>$v){
            foreach($v as $c=>$d){
                $recommend_goods[$k][$c]['original_img']='http://'.$_SERVER['HTTP_HOST'].$d['original_img'];
            }
        }
        $data['banner']=$ad_img;
        $data['config']=$config;
        $data['catenav']=$catenav;
        $data['category']=$cat_path;
        $data['hot_goods']=$hot_goods;
        $data['recommend_goods']=$recommend_goods;
        if($data){
            return json_encode(array('status'=>1,'msg'=>'获取成功','data'=>$data));
        }else{
            return json_encode(array('status'=>0,'msg'=>'获取失败','data'=>''));
        }
    }
    //获取各页面轮播图
    public function bannerImg(){
        $type=I('type/d',1);
        $token=I('get.token');
        if($token !=self::CHECK_TOKEN){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        if($type==1){   //首页轮播图
            $ad_img=M('ad')->where(['enabled'=>1,'pid'=>2])->field('ad_id,ad_code,ad_link')->order('orderby desc')->limit(8)->select();
        }elseif($type==2){//分类页
            $ad_img=M('ad')->where(['enabled'=>1,'pid'=>3])->field('ad_id,ad_code,ad_link')->order('orderby desc')->limit(8)->select();
        }elseif($type==3){ //组合特卖
            $ad_img=M('ad')->where(['enabled'=>1,'pid'=>5])->field('ad_id,ad_code,ad_link')->order('orderby desc')->limit(8)->select();
        }elseif($type==4){  //小镇热卖
            $ad_img=M('ad')->where(['enabled'=>1,'pid'=>6])->field('ad_id,ad_code,ad_link')->order('orderby desc')->limit(8)->select();
        }elseif($type==5){   //团购促销
            $ad_img=M('ad')->where(['enabled'=>1,'pid'=>4])->field('ad_id,ad_code,ad_link')->order('orderby desc')->limit(8)->select();
        }else{
            $ad_img=M('ad')->where(['enabled'=>1,'pid'=>2])->field('ad_id,ad_code,ad_link')->order('orderby desc')->limit(8)->select();
        }
        foreach($ad_img as $k=>$v){
            $ad_img[$k]['ad_code']='http://'.$_SERVER['HTTP_HOST'].$v['ad_code'];
        }
        if($ad_img){
            return json_encode(array('status' => 1,'data'=>$ad_img ,'msg'=>'获取成功'));
        }else{
            return json_encode(array('status' => 0,'data'=>'' ,'msg'=>'暂无图片'));
        }
    }
    //获取分类产品
    public function getshop(){
        header("Access-Control-Allow-Origin: *");
        $data=M('goods')->where(['cat_id'=>I('cat_id/d',191),'is_on_sale'=>1])->field('goods_name,goods_id,shop_price,market_price,cat_id,original_img,sales_sum')->select();
        foreach($data as $k=>$v){
            $data[$k]['original_img']='http://'.$_SERVER['HTTP_HOST'].$v['original_img'];
        }
        return json_encode(array('status' => 1,'data'=>$data ,'msg'=>'获取成功'));
    }
    //首页资讯
    public function Information(){
        header("Access-Control-Allow-Origin: *");
        $page=I('page/d',1);
        $token=I('get.token');
        if($token !=self::CHECK_TOKEN){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        $data2=M('article')->where(['is_open'=>1])->field('article_id,title,content,add_time,click,thumb')->order('add_time desc')->limit((($page-1)*6),($page*6))->select();
        $data=M('article')->where(['is_open'=>1])->field('article_id,title,content,add_time,click,thumb')->order('add_time desc')->select();
        $count=count($data);
        $countPage=ceil($count/6);
        foreach($data2 as $k=>$v){
            $data2[$k]['add_time']=date('Y-m-d H:i',$v['add_time']);
            $data2[$k]['content']=html_entity_decode($v['content']);
            $data2[$k]['thumb']='http://'.$_SERVER['HTTP_HOST'].$v['thumb'];
        }
        $data1['data']=$data2;
        $data1['count']=$count;
        $data1['countPage']=$countPage;
        return json_encode(array('status' => 1,'data'=>$data1 ,'msg'=>'获取成功'));
    }
    //资讯详情
    public function InformationDetail(){
        header("Access-Control-Allow-Origin: *");
        $id=I('get.article_id/d',1);
        $data=M('article')->where(['article_id'=>$id])->field('article_id,title,content,add_time,click,thumb')->find();
        $data['add_time']=date('Y-m-d H:i',$data['add_time']);
        $data['content']=html_entity_decode($data['content']);
        $data['thumb']='http://'.$_SERVER['HTTP_HOST'].$data['thumb'];
        return json_encode(array('status' => 1,'data'=>$data ,'msg'=>'获取成功'));
    }

    //首页商品搜索
    public function getSearch()
    {
        $search=I('get.search');
        $token=I('get.token');
        if($token !='asDFgtRewq'){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        if(empty($search)){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'未填写搜索内容'));
        }
        $where['goods_name']=array('like','%'.$search.'%');
        $where['is_on_sale']=array('eq',1);
        $data=M('goods')->where($where)->order('goods_id desc')->select();
        foreach($data as $k=>$v){
            $data[$k]['goods_content']=html_entity_decode($v['goods_content']);
            $data[$k]['original_img']='http://'.$_SERVER['HTTP_HOST'].$v['original_img'];
        }
        if($data){
            return json_encode(array('status'=>1,'success'=>true,'data'=>$data));
        }else{
            return json_encode(array('status'=>0,'data'=>'','msg'=>'没有符合要求的商品'));
        }
    }

    //首页资讯搜索
    public function getAdSearch()
    {
        $search=I('get.search');
        $token=I('get.token');
        if($token !='asDFgtRewq'){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        if(empty($search)){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'未填写搜索内容'));
        }
        $where['title']=array('like','%'.$search.'%');
        $whereOr['content']=array('like','%'.$search.'%');
        $where['is_open']=array('eq',1);
        $data=M('article')->where($where)->order('add_time desc')->select();
        foreach($data as $k=>$v){
            $data[$k]['add_time']=date('Y-m-d H:i',$v['add_time']);
            $data[$k]['content']=html_entity_decode($v['content']);
            $data[$k]['thumb']='http://'.$_SERVER['HTTP_HOST'].$v['thumb'];
        }
        if($data){
            return json_encode(array('status'=>1,'success'=>true,'data'=>$data));
        }else{
            return json_encode(array('status'=>0,'data'=>'','msg'=>'没有符合要求的信息'));
        }
    }
    //购物车
    public function shopCar(){
        $id=I('get.user_id');
        $token=I('get.token');
        if($token !='asDFgtRewq'){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        if(empty($id)){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'您还未登录，暂无数据'));
        }
        $data="select a.*,b.original_img from tp_cart as a left join tp_goods as b on a.goods_id=b.goods_id where user_id=".$id." order by id desc";
        $data=Db::query($data);
        foreach($data as $k=>$v){
            $data[$k]['original_img']='http://'.$_SERVER['HTTP_HOST'].$v['original_img'];
        }
        if($data){
            return json_encode(array('status'=>1,'msg'=>'获取成功','data'=>$data));
        }else{
            return json_encode(array('status'=>0,'data'=>'','msg'=>'购物车空空如也'));
        }
    }

    /*
     * 组合销售
     */
    public function Combination()
    {
        $token=I('get.token');
        if($token !='asDFgtRewq'){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        $where['is_on_sale']=array('eq',1);
        $where['goods_type_kb']=array('eq',1);
        $data=M('goods')->where($where)->order('on_time desc')->field('goods_id,goods_name,market_price,shop_price,original_img')->select();
        if($data){
            return json_encode(array('status'=>1,'data'=>$data,'msg'=>'获取组合销售产品成功'));
        }else{
            return json_encode(array('status'=>0,'data'=>'','msg'=>'获取组合销售产品失败'));
        }
    }
    /**
     *  公告详情页
     */
    public function notice(){
        return $this->fetch();
    }

    //不带logo的二维码
    public function qrcode($url='http://www.baidu.com',$level=3,$size=4)
    {
        Vendor('phpqrcode.phpqrcode');
        $errorCorrectionLevel =intval($level) ;//容错级别
        $matrixPointSize = intval($size);//生成图片大小
        //生成二维码图片
        $object = new \QRcode();
        $object->png($url, false, $errorCorrectionLevel, $matrixPointSize, 2);
        $object = new \QRcode();
        //打开缓冲区
        ob_start();
        $object->png($url, false, $errorCorrectionLevel, $matrixPointSize, 2);
        //这里就是把生成的图片流从缓冲区保存到内存对象上，使用base64_encode变成编码字符串，通过json返回给页面。
        $imageString = base64_encode(ob_get_contents());
        //关闭缓冲区
        ob_end_clean();
        //把生成的base64字符串返回给前端
        $data = array(
            'code'=>200,
            'data'=>$imageString
        );
        $this->ajaxReturn($data);
    }

  public  function makecode(){
      $token=I('get.token');
      if($token !='asDFgtRewq'){
          return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
      }
      $uid=I('get.user_id');
      $data=M('users')->where(['user_id'=>$uid])->field('head_pic,nickname,sex,user_money,pay_points')->find();
      $qrcode_path=$data['head_pic'];
      $content='http://'.$_SERVER['HTTP_HOST'].'/index.php/home/index/reg/last_uid/'.$uid;
      $matrixPointSize=5;
      $matrixMarginSize=2;
      $errorCorrectionLevel='H';
      $url='public/qrcode/qrcode_'.$uid.'.jpg';
      ob_clean ();
        Vendor('phpqrcode.phpqrcode');
        $object = new \QRcode();
        $qrcode_path_new = './public/qrcode/erweima_'.$uid.'.png';//定义生成二维码的路径及名称
        $object::png($content,$qrcode_path_new, $errorCorrectionLevel, $matrixPointSize, $matrixMarginSize);
        $QR = imagecreatefromstring(file_get_contents($qrcode_path_new));//imagecreatefromstring:创建一个图像资源从字符串中的图像流
        $logo = imagecreatefromstring(file_get_contents($qrcode_path));
        $QR_width = imagesx($QR);// 获取图像宽度函数
        $QR_height = imagesy($QR);//获取图像高度函数
        $logo_width = imagesx($logo);// 获取图像宽度函数
        $logo_height = imagesy($logo);//获取图像高度函数
        $logo_qr_width = $QR_width / 4;//logo的宽度
        $scale = $logo_width / $logo_qr_width;//计算比例
        $logo_qr_height = $logo_height / $scale;//计算logo高度
        $from_width = ($QR_width - $logo_qr_width) / 2;//规定logo的坐标位置
        imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
        Header("Content-type: image/png");
        //$url:定义生成带logo的二维码的地址及名称
        imagepng($QR,$url);
      if($url){
          echo '<img src="/public/qrcode/qrcode_'.$uid.'.jpg">';
          return json_encode(array('status'=>1,'data'=>'http://'.$_SERVER['HTTP_HOST'].'/public/qrcode/qrcode_'.$uid.'.jpg','msg'=>'二维码生成成功'));
      }else{
          return json_encode(array('status'=>0,'data'=>'','msg'=>'二维码生成失败'));
      }

    }

    /**
     * 猜你喜欢
     */
    public function ajax_favorite(){
        $token=I('get.token');
        if($token !=self::CHECK_TOKEN){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        $where = ['is_recommend'=>1,'is_on_sale'=>1,'virtual_indate'=>['exp',' = 0 OR virtual_indate > '.time()]];
        $favourite_goods = Db::name('goods')->where($where)->order('goods_id DESC')->limit(1)->cache(true,TPSHOP_CACHE_TIME)->select();
        foreach($favourite_goods as $k=>$v){
            $favourite_goods[$k]['original_img']='http://'.$_SERVER['HTTP_HOST'].$v['original_img'];
            $favourite_goods[$k]['add_time']=date('Y-m-d H:i',$v['add_time']);
        }
        return json_encode(array('status'=>1,'msg'=>'获取成功','data'=>$favourite_goods));
    }

    public function customerData()
    {
        $uid=I('get.user_id');
        $data=M('users')->where(['user_id'=>$uid])->find();
        $this->assign('data',$data);
        return $this->fetch();
    }
    /*
     * 我的推广分销商
     */
    public function mydistribution(){
        $token=I('get.token');
        if($token !=self::CHECK_TOKEN){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        //客户所属下级
        $uid=I('user_id');
        $data=M('users')->where(['first_leader'=>$uid])->order('reg_time desc')->field('reg_time,user_id,nickname')->select();
        foreach($data as $k=>$v){
            $data[$k]['reg_time']=date('Y-m-d H:i',$v['reg_time']);
            $tid[]=$v['user_id'];
        }
        if(empty($tid)){
            return json_encode(array('status'=>0,'msg'=>'暂无','data'=>''));
        }
        $tids=implode(',',$tid);
        //分销分成比例
        $distribut=tpCache('distribut');
        $rate=round($distribut['first_rate']/100,2);
        //下属所有订单
        $where['user_id']=array('in',$tids);
        $where['order_status']=array('gt',3);
        $where['pay_status']=array('eq',1);
        $sum=M('order')->where($where)->field('user_id,sum(goods_price) as sum')->group('user_id')->select();
        foreach($data as $k=>$v){
                if($data[$k]['user_id']=$sum[$k]['user_id']){
                        $data[$k]['sum']=round($sum[$k]['sum']*$rate,2);
                }
        }
        foreach($data as $k=>$v){
            if(!isset($v['sum'])){
                $data[$k]['sum']=0;
            }
        }
       if($data){
           return json_encode(array('status'=>1,'msg'=>'获取成功','data'=>$data));
       }else{
            return json_encode(array('status'=>0,'msg'=>'暂无数据','data'=>''));
       }
    }
    /**
     *  推广注册
     */
    public function reg(){
        $data=tpCache('shop_info');
        $uid=I('last_uid');
        if(IS_POST){
            $verify_code = I('post.verify');
            $verify = new Verify();
            if (!$verify->check($verify_code))
            {
                return json_encode(array('status'=>0,'data'=>'','msg'=>'验证码错误'));
            }
            $username = I('post.mobile');
            $password = I('post.pwd');
            $password2 = I('post.pwd2');
            $email = I('post.email');
            $first_leader = I('post.first_leader');
            $logic = new UsersLogic();
            $data = $logic->reg($username,$password,$password2,$email,$first_leader);
            $this->ajaxReturn($data);
            exit;
        }
        $this->assign('uid',$uid);
        $this->assign('data',$data);
        return $this->fetch();
    }

    
}