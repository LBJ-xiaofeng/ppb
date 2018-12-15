<?php
namespace app\admin\controller;
use app\admin\logic\GoodsLogic;
use app\admin\logic\SearchWordLogic;
use think\AjaxPage;
use think\Loader;
use think\Page;
use think\Db;

class Goods extends Base {
    
    /**
     *  商品分类列表
     */
    public function categoryList(){                
        $GoodsLogic = new GoodsLogic();               
        $cat_list = $GoodsLogic->goods_cat_list();
        $this->assign('cat_list',$cat_list);        
        return $this->fetch();
    }
    
    /**
     * 添加修改商品分类
     */
    public function addEditCategory(){
            $GoodsLogic = new GoodsLogic();        
            if(IS_GET)
            {
                if(I('get.act')=='add'){
                    $act='add';
                    $this->assign('act',$act);
                    return $this->fetch('_category');
                }else{
                    $act='upd';
                    $goods_category_info = D('GoodsCategory')->where('id='.I('GET.id',0))->find();
                    $level_cat = $GoodsLogic->find_parent_cat($goods_category_info['id']); // 获取分类默认选中的下拉框

                    $cat_list = M('goods_category')->where("parent_id = 0")->select(); // 已经改成联动菜单
                    $this->assign('level_cat',$level_cat);
                    $this->assign('act',$act);
                    $this->assign('cat_list',$cat_list);
                    $this->assign('goods_category_info',$goods_category_info);
                    return $this->fetch('_category');
                }
            }else{
                if(I('post.act')=='add'){
//                    if(I('post.commission_rate')>10){
//                        return $this->error('佣金比例不能大于10');
//                    }else{
                        $add=I('post.');
                        $add['level']=1;
                        $res=M('goods_category')->add($add);
                        if($res){
                            return $this->success('添加成功',U('Goods/categoryList'));
                        }else{
                            return $this->error('添加失败');
                        }
//                    }
                }else{
//                    if(I('post.commission_rate')>10){
//                        return $this->error('佣金比例不能大于10');
//                    }else{
                        $res=M('goods_category')->where(['id'=>I('post.id',0)])->save(I('post.'));
                        if($res){
                            return $this->success('修改成功',U('Goods/categoryList'));
                        }else{
                            return $this->error('修改失败');
                        }
//                    }
                }
            }
    }
    
    /**
     * 获取商品分类 的帅选规格 复选框
     */
    public function ajaxGetSpecList(){
        $GoodsLogic = new GoodsLogic();
        $_REQUEST['category_id'] = $_REQUEST['category_id'] ? $_REQUEST['category_id'] : 0;
        $filter_spec = M('GoodsCategory')->where("id = ".$_REQUEST['category_id'])->getField('filter_spec');        
        $filter_spec_arr = explode(',',$filter_spec);        
        $str = $GoodsLogic->GetSpecCheckboxList($_REQUEST['type_id'],$filter_spec_arr);  
        $str = $str ? $str : '没有可帅选的商品规格';
        exit($str);        
    }
 
    /**
     * 获取商品分类 的帅选属性 复选框
     */
    public function ajaxGetAttrList(){
        $GoodsLogic = new GoodsLogic();
        $_REQUEST['category_id'] = $_REQUEST['category_id'] ? $_REQUEST['category_id'] : 0;
        $filter_attr = M('GoodsCategory')->where("id = ".$_REQUEST['category_id'])->getField('filter_attr');        
        $filter_attr_arr = explode(',',$filter_attr);        
        $str = $GoodsLogic->GetAttrCheckboxList($_REQUEST['type_id'],$filter_attr_arr);          
        $str = $str ? $str : '没有可帅选的商品属性';
        exit($str);        
    }    
    
    /**
     * 删除分类
     */
    public function delGoodsCategory(){
        $ids = I('post.ids','');
        empty($ids) &&  $this->ajaxReturn(['status' => -1,'msg' =>"非法操作！",'data'  =>'']);
        // 判断子分类
        $count = Db::name("goods_category")->where("parent_id = {$ids}")->count("id");
        $count > 0 && $this->ajaxReturn(['status' => -1,'msg' =>'该分类下还有分类不得删除!']);
        // 判断是否存在商品
        $goods_count = Db::name('Goods')->where("cat_id = {$ids}")->count('1');
        $goods_count > 0 && $this->ajaxReturn(['status' => -1,'msg' =>'该分类下有商品不得删除!']);
        // 删除分类
        DB::name('goods_category')->where('id',$ids)->delete();
        $this->ajaxReturn(['status' => 1,'msg' =>'操作成功','url'=>U('Admin/Goods/categoryList')]);
    }
    
    
    /**
     *  商品列表
     */
    public function goodsList(){
        $cat_list = M('goods_category')->where("is_show=1")->field('id,mobile_name,parent_id')->select(); // 已经改成联动菜单
        $cat_list=list_to_tree($cat_list,'id','parent_id','child',0);
        $this->assign('cat_list',$cat_list);
        return $this->fetch();
    }
    
    /**
     *  商品列表
     */
    public function ajaxGoodsList(){            
        
        $where = ' 1 = 1 '; // 搜索条件                
        I('intro')    && $where = "$where and ".I('intro')." = 1" ;        
        I('brand_id') && $where = "$where and brand_id = ".I('brand_id') ;
        (I('is_on_sale') !== '') && $where = "$where and is_on_sale = ".I('is_on_sale') ;
        $cat_id = I('cat_id');
        ($cat_id !== '') && $where = "$where and cat_id = ".$cat_id ;
        // 关键词搜索               
        $key_word = I('key_word') ? trim(I('key_word')) : '';
        if($key_word)
        {
            $where = "$where and (goods_name like '%$key_word%' or goods_sn like '%$key_word%')" ;
        }
        
        if($cat_id > 0)
        {
            $grandson_ids = getCatGrandson($cat_id); 
            $where .= " and cat_id in(".  implode(',', $grandson_ids).") "; // 初始化搜索条件
        }
        
        $count = M('Goods')->where($where)->count();
        $Page  = new AjaxPage($count,20);
        /**  搜索条件下 分页赋值
        foreach($condition as $key=>$val) {
            $Page->parameter[$key]   =   urlencode($val);
        }
        */
        $show = $Page->show();
        $order_str = "`{$_POST['orderby1']}` {$_POST['orderby2']}";
        $goodsList = M('Goods')->where($where)->order($order_str)->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('cat_list',$cat_list);
        $this->assign('goodsList',$goodsList);
        $this->assign('page',$show);// 赋值分页输出
        return $this->fetch();
    }
    
    
    public function stock_list(){
    	$model = M('stock_log');
    	$map = array();
    	$mtype = I('mtype');
    	if($mtype == 1){
    		$map['stock'] = array('gt',0);
    	}
    	if($mtype == -1){
    		$map['stock'] = array('lt',0);
    	}
    	$goods_name = I('goods_name');
    	if($goods_name){
    		$map['goods_name'] = array('like',"%$goods_name%");
    	}
    	$ctime = urldecode(I('ctime'));
    	if($ctime){
    		$gap = explode(' - ', $ctime);
    		$this->assign('start_time',$gap[0]);
    		$this->assign('end_time',$gap[1]);
    		$this->assign('ctime',$gap[0].' - '.$gap[1]);
    		$map['ctime'] = array(array('gt',strtotime($gap[0])),array('lt',strtotime($gap[1])));
    	}
    	$count = $model->where($map)->count();
    	$Page  = new Page($count,20);
    	$show = $Page->show();
    	$this->assign('pager',$Page);
    	$this->assign('page',$show);// 赋值分页输出
    	$stock_list = $model->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
    	$this->assign('stock_list',$stock_list);
    	return $this->fetch();
    }

    /**
     * 添加修改商品
     */
    public function addEditGoods()
    {
        $GoodsLogic = new GoodsLogic();
        $Goods = new \app\admin\model\Goods();
        $goods_id = I('goods_id');
        $type = $goods_id > 0 ? 2 : 1; // 标识自动验证时的 场景 1 表示插入 2 表示更新
        //ajax提交验证
        if ((I('is_ajax') == 1) && IS_POST) {
            // 数据验证
            $return_url =  U('admin/Goods/goodsList');
            $data = input('post.');
//            print_r($data);die;
            $data['on_time'] =time() ;
            if ($type == 2) {
                M('goods')->where('goods_id',$goods_id)->save($data);
                // 修改商品后购物车的商品价格也修改一下
                M('cart')->where("goods_id = $goods_id and spec_key = ''")->save(array(
                    'member_goods_price' => I('shop_price'), // 价
                ));
            } else {
               M('goods')->add($data);
            }
            $return_arr = array(
                'status' => 1,
                'msg' => '操作成功',
                'data' => array('url' => $return_url),
            );
            $this->ajaxReturn($return_arr);
        }

        $goodsInfo = M('Goods')->where('goods_id=' . I('GET.id', 0))->find();
        if ($goodsInfo['price_ladder']) {
            $goodsInfo['price_ladder'] = unserialize($goodsInfo['price_ladder']);
        }
        $level_cat = $GoodsLogic->find_parent_cat($goodsInfo['cat_id']); // 获取分类默认选中的下拉框
        $level_cat2 = $GoodsLogic->find_parent_cat($goodsInfo['extend_cat_id']); // 获取分类默认选中的下拉框
        $cat_list = M('goods_category2')->where(["is_show"=>1,"parent_id"=>0])->field('id,mobile_name')->select(); // 已经改成联动菜单
        // $cat_list=list_to_tree($cat_list,'id','parent_id','child',0);
        $brandList = $GoodsLogic->getSortBrands();
        $goodsType = M("GoodsType")->select();
        $suppliersList = M("suppliers")->select();
        $plugin_shipping = M('plugin')->where(array('type' => array('eq', 'shipping')))->select();//插件物流
        $shipping_area = D('Shipping_area')->getShippingArea();//配送区域
        $goods_shipping_area_ids = explode(',', $goodsInfo['shipping_area_ids']);
        $this->assign('goods_shipping_area_ids', $goods_shipping_area_ids);
        $this->assign('shipping_area', $shipping_area);
        $this->assign('plugin_shipping', $plugin_shipping);
        $this->assign('suppliersList', $suppliersList);
        $this->assign('level_cat', $level_cat);
        $this->assign('level_cat2', $level_cat2);
        $this->assign('cat_list', $cat_list);
        $this->assign('brandList', $brandList);
        $this->assign('goodsType', $goodsType);
        $this->assign('goodsInfo', $goodsInfo);  // 商品详情
        $goodsImages = M("GoodsImages")->where('goods_id =' . I('GET.id', 0))->select();
        $this->assign('goodsImages', $goodsImages);  // 商品相册
        return $this->fetch('_goods');
    }

    //分类联动
    public function changeData(){
        $pid=I('pid');
        $data=M('goods_category2')->where(['is_show'=>1,'parent_id'=>$pid])->field('id,mobile_name')->select();
        return json_encode(array('status'=>1,'data'=>$data,'msg'=>'SUCCESS'));
    }
          
    /**
     * 商品类型  用于设置商品的属性
     */
    public function goodsTypeList(){
        $model = M("GoodsType");                
        $count = $model->count();        
        $Page = $pager = new Page($count,14);
        $show  = $Page->show();
        $goodsTypeList = $model->order("id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('pager',$pager);
        $this->assign('show',$show);
        $this->assign('goodsTypeList',$goodsTypeList);
        return $this->fetch('goodsTypeList');
    }

    /**
     * 添加修改编辑  商品属性类型
     */
    public function addEditGoodsType()
    {
        $id = $this->request->param('id', 0);

        $model = M("GoodsType");
        $goodsType = $model->find($id);
        if (IS_POST) {
            $data = $this->request->post();
            if ($id){
                $res=DB::name('GoodsType')->update($data);
            }else{
                $typeid = $this->request->param('goods_id', 0);
                $count=M('goods_type')->where(['goods_id'=>$typeid])->count();
                if($count>0){
                  return  $this->error('该商品已存在模型');
                }
                   $res= DB::name('GoodsType')->insert($data);
            }
            if($res){
                $this->success("操作成功!!!", U('Admin/Goods/goodsTypeList'));
                exit;
            }else{
                $this->error("操作失败!!!");
                exit;
            }
        }
        $goods=M('goods')->where(['is_on_sale'=>1])->field('goods_id,goods_name')->select();
        $this->assign('goods', $goods);
        $this->assign('goodsType', $goodsType);
        return $this->fetch('_goodsType');
    }
    
    /**
     * 商品属性列表
     */
    public function goodsAttributeList(){
            $id=I('type_id');
            $sql="select a.*,b.id,b.name from tp_goods_attr as a left join tp_goods_type as b on a.goods_type_id=b.id where a.goods_type_id=".$id;
            $data=M('goods_attr')->query($sql);
            $this->assign('goodsAttributeList',$data);
        $this->assign('id',$id);
        return $this->fetch();
    }
    
    /**
     * 添加修改编辑  商品属性
     */
    public  function addEditGoodsAttribute(){


            if(IS_POST)//ajax提交验证
            {
                $model = M('goods_attr');
                $post_data = input('post.');
                $attr_id = I('goods_attr_id/d',0);
                $tid = I('type_id/d',0);
                if ($attr_id == '') {
                    $res=$model->add($post_data);
                }else{
                    $res=$model->where(['goods_attr_id'=>$attr_id])->save($post_data);
                 }
                if($res){
                    return $this->success('操作成功',U('Admin/Goods/goodsAttributeList',array('type_id'=>$tid)));
                }else{
                    return $this->error('操作失败');
                }
            }else{
                $type_id=I('type_id');
                $attr_id = I('goods_attr_id/d',0);
                $goodsTypeList = M("goods_type")->where(['id'=>$type_id])->field('id,name')->find();
                $this->assign('goodsTypeList',$goodsTypeList);
                $this->assign('id',$type_id);
                $goodsAttribute = M('goods_attr')->where(['goods_attr_id'=>$attr_id])->find();
                $this->assign('goodsAttribute',$goodsAttribute);
                return $this->fetch('_goodsAttribute');
            }

    }  
    
    /**
     * 更改指定表的指定字段
     */
    public function updateField(){
        $primary = array(
                'goods' => 'goods_id',
                'goods_category' => 'id',
                'brand' => 'id',            
                'goods_attribute' => 'attr_id',
        		'ad' =>'ad_id',            
        );        
        $model = D($_POST['table']);
        $model->$primary[$_POST['table']] = $_POST['id'];
        $model->$_POST['field'] = $_POST['value'];        
        $model->save();   
        $return_arr = array(
            'status' => 1,
            'msg'   => '操作成功',                        
            'data'  => array('url'=>U('Admin/Goods/goodsAttributeList')),
        );
        $this->ajaxReturn($return_arr);
    }

    /**
     * 动态获取商品属性输入框 根据不同的数据返回不同的输入框类型
     */
    public function ajaxGetAttrInput(){
        $GoodsLogic = new GoodsLogic();
        $str = $GoodsLogic->getAttrInput($_REQUEST['goods_id'],$_REQUEST['type_id']);
        exit($str);
    }
        
    /**
     * 删除商品
     */
    public function delGoods()
    {
        $ids = I('post.ids','');
        empty($ids) &&  $this->ajaxReturn(['status' => -1,'msg' =>"非法操作！",'data'  =>'']);
        $goods_ids = rtrim($ids,",");
        // 判断此商品是否有订单
//        $ordergoods_count = Db::name('OrderGoods')->whereIn('goods_id',$goods_ids)->group('goods_id')->getField('goods_id',true);
//        if($ordergoods_count)
//        {
//            $goods_count_ids = implode(',',$ordergoods_count);
//            $this->ajaxReturn(['status' => -1,'msg' =>"ID为【{$goods_count_ids}】的商品有订单,不得删除!",'data'  =>'']);
//        }
//         // 商品团购
//        $groupBuy_goods = M('group_buy')->whereIn('goods_id',$goods_ids)->group('goods_id')->getField('goods_id',true);
//        if($groupBuy_goods)
//        {
//            $groupBuy_goods_ids = implode(',',$groupBuy_goods);
//            $this->ajaxReturn(['status' => -1,'msg' =>"ID为【{$groupBuy_goods_ids}】的商品有团购,不得删除!",'data'  =>'']);
//        }
        // 删除此商品        
        M("Goods")->whereIn('goods_id',$goods_ids)->delete();  //商品表
        M("cart")->whereIn('goods_id',$goods_ids)->delete();  // 购物车
        M("comment")->whereIn('goods_id',$goods_ids)->delete();  //商品评论
        M("goods_consult")->whereIn('goods_id',$goods_ids)->delete();  //商品咨询
        M("goods_images")->whereIn('goods_id',$goods_ids)->delete();  //商品相册
        M("spec_goods_price")->whereIn('goods_id',$goods_ids)->delete();  //商品规格
        M("spec_image")->whereIn('goods_id',$goods_ids)->delete();  //商品规格图片
//        M("goods_attr")->whereIn('goods_id',$goods_ids)->delete();  //商品属性
        M("goods_collect")->whereIn('goods_id',$goods_ids)->delete();  //商品收藏

        $this->ajaxReturn(['status' => 1,'msg' => '操作成功','url'=>U("Admin/goods/goodsList")]);
    }
    
    /**
     * 删除商品类型 
     */
    public function delGoodsType()
    {
        // 判断 商品规格
        $id = $this->request->param('id');
        $count = M("GoodsAttr")->where("goods_type_id = {$id}")->count("1");
        $count > 0 && $this->error('该类型下有商品属性不得删除!',U('Admin/Goods/goodsTypeList'));
        // 判断 商品属性        
        $count = M("GoodsAttribute")->where("type_id = {$id}")->count("1");
        $count > 0 && $this->error('该类型下有商品规格不得删除!',U('Admin/Goods/goodsTypeList'));
        // 删除分类
        M('GoodsType')->where("id = {$id}")->delete();
        $this->success("操作成功!!!",U('Admin/Goods/goodsTypeList'));
    }    

    /**
     * 删除商品属性
     */
    public function delGoodsAttribute()
    {
        $ids = I('post.ids','');
        $id = I('post.id','');
        empty($ids) &&  $this->ajaxReturn(['status' => -1,'msg' =>"非法操作！"]);
        M('goods_attr')->whereIn('goods_attr_id',$ids)->delete();
        $this->ajaxReturn(['status' => 1,'msg' => "操作成功!",'url'=>U('Admin/Goods/goodsAttributeList',array('type_id'=>$id))]);
    }            
    
    /**
     * 删除商品规格
     */
    public function delGoodsSpec()
    {
        $ids = I('post.ids','');
        $id = I('post.type_id','');
        empty($ids) &&  $this->ajaxReturn(['status' => -1,'msg' =>"非法操作！"]);
        // 删除分类
        M('goods_attribute')->where('attr_id',$ids)->delete();
        return $this->success('操作成功!!!',U('Admin/Goods/specList',array('type_id'=>$id)));
    } 
    
    /**
     * 品牌列表
     */
    public function brandList(){  
        $model = M("Brand"); 
        $where = "";
        $keyword = I('keyword');
        $where = $keyword ? " name like '%$keyword%' " : "";
        $count = $model->where($where)->count();
        $Page = $pager = new Page($count,10);        
        $brandList = $model->where($where)->order("`sort` asc")->limit($Page->firstRow.','.$Page->listRows)->select();
        $show  = $Page->show(); 
        $cat_list = M('goods_category')->where("parent_id = 0")->getField('id,name'); // 已经改成联动菜单
        $this->assign('cat_list',$cat_list);       
        $this->assign('pager',$pager);
        $this->assign('show',$show);
        $this->assign('brandList',$brandList);
        return $this->fetch('brandList');
    }
    
    /**
     * 添加修改编辑  商品品牌
     */
    public  function addEditBrand(){
            $id = I('id');            
            if(IS_POST)
            {
               	$data = I('post.');
                $brandVilidate = Loader::validate('Brand');
                if(!$brandVilidate->batch()->check($data)){
                    $return = ['status'=>0,'msg'=>'操作失败','result'=>$brandVilidate->getError()];
                    $this->ajaxReturn($return);
                }
                if($id){
                	M("Brand")->update($data);
                }else{
                	M("Brand")->insert($data);
                }
                $this->ajaxReturn(['status'=>1,'msg'=>'操作成功','result'=>'']);
            }           
           $cat_list = M('goods_category')->where("parent_id = 0")->select(); // 已经改成联动菜单
           $this->assign('cat_list',$cat_list);           
           $brand = M("Brand")->find($id);             
           $this->assign('brand',$brand);
           return $this->fetch('_brand');
    }    
    
    /**
     * 删除品牌
     */
    public function delBrand()
    {
        $ids = I('post.ids','');
        empty($ids) && $this->ajaxReturn(['status' => -1,'msg' => '非法操作！']);
        $brind_ids = rtrim($ids,",");
        // 判断此品牌是否有商品在使用
        $goods_count = Db::name('Goods')->whereIn("brand_id",$brind_ids)->group('brand_id')->getField('brand_id',true);
        $use_brind_ids = implode(',',$goods_count);
        if($goods_count)
        {
            $this->ajaxReturn(['status' => -1,'msg' => 'ID为【'.$use_brind_ids.'】的品牌有商品在用不得删除!','data'  =>'']);
        }
        $res=Db::name('Brand')->whereIn('id',$brind_ids)->delete();
        if($res){
            $this->ajaxReturn(['status' => 1,'msg' => '操作成功','url'=>U("Admin/goods/brandList")]);
        }
        $this->ajaxReturn(['status' => -1,'msg' => '操作失败','data'  =>'']);
    }      
    
    /**
     * 商品规格列表    
     */
    public function specList(){
        $id=I('type_id');
        $sql="select a.*,b.id,b.name from tp_goods_attribute as a left join tp_goods_type as b on a.type_id=b.id where a.type_id=".$id;
        $data=M('goods_attribute')->query($sql);
        $this->assign('goodsAttributeList',$data);
        $this->assign('id',$id);
        return $this->fetch();
    }
    
    
    /**
     *  商品规格列表
     */
    public function ajaxSpecList(){ 
        //ob_start('ob_gzhandler'); // 页面压缩输出
        $where = ' 1 = 1 '; // 搜索条件                        
        I('type_id')   && $where = "$where and type_id = ".I('type_id') ;        
        // 关键词搜索               
        $model = D('spec');
        $count = $model->where($where)->count();
        $Page       = new AjaxPage($count,13);
        $show = $Page->show();
        $specList = $model->where($where)->order('`type_id` desc')->limit($Page->firstRow.','.$Page->listRows)->select();        
        $GoodsLogic = new GoodsLogic();        
        foreach($specList as $k => $v)
        {       // 获取规格项     
                $arr = $GoodsLogic->getSpecItem($v['id']);
                $specList[$k]['spec_item'] = implode(' , ', $arr);
        }
        
        $this->assign('specList',$specList);
        $this->assign('page',$show);// 赋值分页输出
        $goodsTypeList = M("GoodsType")->select(); // 规格分类
        $goodsTypeList = convert_arr_key($goodsTypeList, 'id');
        $this->assign('goodsTypeList',$goodsTypeList);        
        return $this->fetch();
    }

    /**
     * 添加修改编辑  商品规格
     */
    public  function addEditSpec(){


        if(IS_POST)//ajax提交验证
        {
            $model = M('goods_attribute');
            $post_data = input('post.');
            $attr_id = I('id/d',0);
            $tid = I('type_id/d',0);
//            print_r($post_data);die;
            if ($attr_id == '') {
                $res=$model->add($post_data);
            }else{
                $res=$model->where(['attr_id'=>$attr_id])->save($post_data);
            }
            if($res){
                return $this->success('操作成功',U('Admin/Goods/specList',array('type_id'=>$tid)));
            }else{
                return $this->error('操作失败');
            }
        }else{
            $type_id=I('type_id');
            $attr_id = I('id/d',0);
            $goodsTypeList = M("goods_type")->where(['id'=>$type_id])->field('id,name')->find();
            $this->assign('goodsTypeList',$goodsTypeList);
            $this->assign('id',$type_id);
            $goodsAttribute = M('goods_attribute')->where(['attr_id'=>$attr_id])->find();
            $this->assign('spec',$goodsAttribute);
            return $this->fetch('_spec');
        }
    }  
    
    
    /**
     * 动态获取商品规格选择框 根据不同的数据返回不同的选择框
     */
    public function ajaxGetSpecSelect(){
        $goods_id = I('get.goods_id/d') ? I('get.goods_id/d') : 0;        
        $GoodsLogic = new GoodsLogic();
        //$_GET['spec_type'] =  13;
        $specList = M('Spec')->where("type_id = ".I('get.spec_type/d'))->order('`order` desc')->select();
        foreach($specList as $k => $v)        
            $specList[$k]['spec_item'] = M('SpecItem')->where("spec_id = ".$v['id'])->order('id')->getField('id,item'); // 获取规格项                
        
        $items_id = M('SpecGoodsPrice')->where('goods_id = '.$goods_id)->getField("GROUP_CONCAT(`key` SEPARATOR '_') AS items_id");
        $items_ids = explode('_', $items_id);       
        
        // 获取商品规格图片                
        if($goods_id)
        {
           $specImageList = M('SpecImage')->where("goods_id = $goods_id")->getField('spec_image_id,src');                 
        }        
        $this->assign('specImageList',$specImageList);
        
        $this->assign('items_ids',$items_ids);
        $this->assign('specList',$specList);
        return $this->fetch('ajax_spec_select');        
    }    
    
    /**
     * 动态获取商品规格输入框 根据不同的数据返回不同的输入框
     */    
    public function ajaxGetSpecInput(){     
         $GoodsLogic = new GoodsLogic();
         $goods_id = I('goods_id/d') ? I('goods_id/d') : 0;
         $str = $GoodsLogic->getSpecInput($goods_id ,I('post.spec_arr/a',[[]]));
         exit($str);   
    }
    
    /**
     * 删除商品相册图
     */
    public function del_goods_images()
    {
        $path = I('filename','');
        M('goods_images')->where("image_url = '$path'")->delete();
    }

    /**
     * 初始化商品关键词搜索
     */
    public function initGoodsSearchWord(){
        $searchWordLogic = new SearchWordLogic();
        $successNum = $searchWordLogic->initGoodsSearchWord();
        $this->success('成功初始化'.$successNum.'个搜索关键词');
    }

    /**
     * 初始化地址json文件
     */
    public function initLocationJsonJs()
    {
        $goodsLogic = new GoodsLogic();
        $region_list = $goodsLogic->getRegionList();//获取配送地址列表
        file_put_contents(ROOT_PATH."public/js/locationJson.js", "var locationJsonInfoDyr = ".json_encode($region_list, JSON_UNESCAPED_UNICODE).';');
        $this->success('初始化地区json.js成功。文件位置为'.ROOT_PATH."public/js/locationJson.js");
    }

    /*
     * 店铺列表
     */
    public function shopList(){
        if(IS_POST){
           $key=I('keyword');
            $where['shop_name']=array('like','%'.$key.'%');
            $whereOr['shop_address']=array('like','%'.$key.'%');
            $count=M('shop_address')->where($where)->whereOr($whereOr)->count();
            $Page  = new AjaxPage($count,20);
            $show = $Page->show();
            $shopList = M('shop_address')->where($where)->whereOr($whereOr)->order('sort asc')->limit($Page->firstRow.','.$Page->listRows)->select();
        }else{
            $count=M('shop_address')->count();
            $Page  = new AjaxPage($count,20);
            $show = $Page->show();
            $shopList = M('shop_address')->order('sort asc')->limit($Page->firstRow.','.$Page->listRows)->select();
        }
        $this->assign('pager',$Page);
        $this->assign('show',$show);
        $this->assign('shopList',$shopList);
        return  $this->fetch();
    }
    //新增、编辑
    public function addEditShop(){
        if(IS_POST){
            $data=I('post.');
            if($data['act']=='save' && !empty($data['id'])){
                $res=M('shop_address')->where('id',$data['id'])->save($data);
            }else{
                $res=M('shop_address')->add($data);
            }
            if($res){
                return json_encode(array('status'=>1,'msg'=>'操作成功'));
//                return $this->success('操作成功',U('admin/Goods/shopList'));
            }else{
//                return $this->success('操作失败');
                return json_encode(array('status'=>1,'msg'=>'操作失败'));
            }
        }else{
            $data=I('get.');
            if($data['p']=='add'){
                $act['act']='add';
            }elseif($data['p']=='save'){
                $act=M('shop_address')->where(['id'=>$data['id']])->find();
                $act['act']='save';
            }elseif($data['p']=='del'){
                $res=M('shop_address')->where(['id'=>$data['id']])->delete();
                if($res){
                    return $this->success('删除成功',U('admin/Goods/shopList'));
                }else{
                    return $this->success('删除失败');
                }
            }
           $this->assign('data',$act);
            return $this->fetch();
        }
    }
    //编辑分类

        public function categoryEdit(){
        if(IS_POST){
            $referurl =  isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : U("Tools/categoryList");
            $data=I('post.');
            $image=$this->request->file('image');
            if(!isset($image) || empty($image)){
                 $data['image']=M("goods_category2")->where(['id'=>$data['id']])->getField('image');
            }else{
                 $img=$this->upload($image);
                 $data['image']=$img;
            }
           
            $res=M('goods_category2')->where(['id'=>$data['id']])->save($data);
            if(!$res){
                $this->success('编辑失败', $referurl);
            }else{
                $this->success('编辑成功');
            }
        }else{
            $id=I('id');
            $catename=M('goods_category2')->where(['id'=>$id])->find();
            $this->assign('data',$catename);
            return $this->fetch();
        }
        
    }
    //分类
    public function categoryLists(){

        $parent_id = I('parent_id',0);
        $id = I('id',0);
        if($id){
            $catename=M('goods_category2')->where(['id'=>$id])->find();
        }
        if($parent_id == 0){
            $parent = array('id'=>0,'name'=>"已是第一级",'level'=>0);
        }else{
            $parent = M('goods_category2')->where("id" ,$parent_id)->find();
        }
        $names = $this->getParentcategoryList($parent_id);
        if(count($names) > 0){
            $names = array_reverse($names);
            $parent_path = implode($names, '>');
        }
        $region = M('goods_category2')->where("parent_id" , $parent_id)->select();
        $this->assign('parent',$parent);
        $this->assign('parent_path',$parent_path);
        $this->assign('region',$region);
        $this->assign('catename',$catename);
        return $this->fetch();
    }
    function getParentcategoryList($parent_id){
        $names = array();
        $region =  M('goods_category2')->where(array('id'=>$parent_id))->find();
        array_push($names,$region['name']);
        if($region['parent_id'] != 0){
            $nregion = $this->getParentcategoryList($region['parent_id']);
            if(!empty($nregion)){
                $names = array_merge($names, $nregion);
            }
        }
        return $names;
    }
    public function categoryListHandle(){
        $data = I('post.');
        $id = I('id');
        $image=$this->request->file('image');
        $img=$this->upload($image);
        $data['image']=$img;
//      print_r($data);die;
        $referurl =  isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : U("Tools/categoryList");
        if(empty($id)){
            if(empty($data['mobile_name'])){
                $this->error("请填写名称", $referurl);
            }else{
                $res = M('goods_category2')->where("parent_id = ".$data['parent_id']." and mobile_name='".$data['mobile_name']."'")->find();
                if(empty($res)){
                    M('goods_category2')->cache(true)->add($data);
                    $this->success("操作成功", $referurl);
                }else{
                    $this->error("该分类下已有,请不要重复添加", $referurl);
                }
            }
        }else{
            M('goods_category2')->where("id=$id")->cache(true)->delete();
            $this->success("操作成功", $referurl);
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

}