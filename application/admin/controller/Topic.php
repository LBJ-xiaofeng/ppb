<?php
namespace app\admin\controller;
use think\Page;

class Topic extends Base {

    public function index(){
        return $this->fetch();
    }
    
    public function topic(){
    	$act = I('get.act','add');
    	$this->assign('act',$act);
    	$topic_id = I('get.topic_id');
    	$topic_info = array();
    	if($topic_id){
    		$topic_info = D('topic')->where('topic_id='.$topic_id)->find();
    		$this->assign('info',$topic_info);
    	}
    	
    	$this->assign("URL_upload", U('Admin/Ueditor/imageUp',array('savepath'=>'topic')));
    	$this->assign("URL_fileUp", U('Admin/Ueditor/fileUp',array('savepath'=>'topic')));
    	$this->assign("URL_scrawlUp", U('Admin/Ueditor/scrawlUp',array('savepath'=>'topic')));
    	$this->assign("URL_getRemoteImage", U('Admin/Ueditor/getRemoteImage',array('savepath'=>'topic')));
    	$this->assign("URL_imageManager", U('Admin/Ueditor/imageManager',array('savepath'=>'topic')));
    	$this->assign("URL_imageUp", U('Admin/Ueditor/imageUp',array('savepath'=>'topic')));
    	$this->assign("URL_getMovie", U('Admin/Ueditor/getMovie',array('savepath'=>'topic')));
    	$this->assign("URL_Home", "");
    	return $this->fetch();
    }
    
    public function topicList(){
    	$Ad =  M('topic');
	$p = $this->request->param('p');
    	$res = $Ad->order('ctime')->page($p.',10')->select();
    	if($res){
    		foreach ($res as $val){
    			$val['topic_state'] = $val['topic_state']>1 ? '已发布' : '未发布';
    			$val['ctime'] = date('Y-m-d H:i',$val['ctime']);
    			$list[] = $val;
    		}
    	}
    	$this->assign('list',$list);// 赋值数据集
    	$count = $Ad->count();// 查询满足要求的总记录数
    	$Page = new Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
    	$show = $Page->show();// 分页显示输出
	$this->assign('pager',$Page);
    	$this->assign('page',$show);// 赋值分页输出
    	return $this->fetch();
    }
    
    public function topicHandle(){
    	$data = I('post.');       
        $data['topic_content'] = $_POST['topic_content']; // 这个内容不做转义        
    	if($data['act'] == 'add'){
    		$data['ctime'] = time();
    		$r = D('topic')->add($data);
    	}
    	if($data['act'] == 'edit'){
    		$r = D('topic')->where('topic_id='.$data['topic_id'])->save($data);
    	}
    	 
    	if($data['act'] == 'del'){
    		$r = D('topic')->where('topic_id='.$data['topic_id'])->delete();
    		if($r) exit(json_encode(1));
    	}
    	 
    	if($r !== false){
			$this->ajaxReturn(['status'=>1,'msg'=>'操作成功','result'=>'']);
    	}else{
			$this->ajaxReturn(['status'=>0,'msg'=>'操作失败','result'=>'']);
    	}
    }

	/*
	 * 物品种类列表
	 */
	public function brandsList(){
		$data=M('goods_category')->order('id asc')->field('id,mobile_name,is_show')->select();
		// print_r($data);die;
		// $cate=list_to_tree($data, 'id','parent_id',$child = 'child',$root=0);
		$this->assign('cate',$data);
		return $this->fetch();
	}
	//品牌查看
	public function brandsDetail(){
        $cat_id=I('cat_id');
		$where="cat_id=$cat_id";
		$keywords = trim(I('keywords'));
		$keywords && $where.=" and name like '%$keywords%' ";
		$count=M('brand')->where($where)->count();
		$Page = new Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();// 分页显示输出
		$data=M('brand')->where($where)->order('id asc')->field('id,name,url,add_time')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('count',$count);
		$this->assign('cat_id',$cat_id);
		$this->assign('pager',$Page);
		$this->assign('show',$show);
		$this->assign('data',$data);
		return $this->fetch();
	}
	//品牌操作
	public function addEditBrands(){
		if(IS_GET){
			$id=I('get.id');
			$cid=I('get.cat_id');
			$act=I('get.act');
			if($id){
                $brand=M('brand')->where(['id'=>$id])->field('id,name,url')->find();
				$this->assign('brand',$brand);
			}
			$this->assign('act',$act);
			$this->assign('cat_id',$cid);
            return $this->fetch();
		}else{
          $data=I('post.');
		  $data['add_time']=time();
		  if($data['id']){
			  if($data['act']=='edit'){
				  $res=M('brand')->where(['id'=>$data['id']])->save($data);
			  }elseif($data['act']=='del'){
				  $res=M('brand')->where(['id'=>$data['id']])->delete();
			  }
		  }	else{
			  if($data['act']=='add'){
				  $res=M('brand')->add($data);
			  }
		  }
			if($res){
				return json_encode(array('status'=>1,'msg'=>'操作成功'));
			}else{
				return json_encode(array('status'=>0,'msg'=>'操作失败'));
			}
		}
	}
	/*
	 * 种类列表
	 */
	public function attrDetail(){
		$cat_id=I('cat_id');
		$where="cat_id=$cat_id";
		$keywords = trim(I('keywords'));
		$keywords && $where.=" and attr_name like '%$keywords%' ";
		$count=M('goods_attr')->where($where)->count();
		$Page = new Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();// 分页显示输出
		$data=M('goods_attr')->where($where)->order('goods_attr_id asc')->field('goods_attr_id,attr_name')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('count',$count);
		$this->assign('cat_id',$cat_id);
		$this->assign('pager',$Page);
		$this->assign('show',$show);
		$this->assign('data',$data);
		return $this->fetch();
	}
	//种类操作
	public function addEditAttr(){
		if(IS_GET){
			$id=I('get.id');
			$cid=I('get.cat_id');
			$act=I('get.act');
			if($id){
				$brand=M('goods_attr')->where(['goods_attr_id'=>$id])->field('goods_attr_id,attr_name')->find();
				$this->assign('brand',$brand);
			}
			$this->assign('act',$act);
			$this->assign('cat_id',$cid);
			return $this->fetch();
		}else{
			$data=I('post.');
			if($data['id']){
				if($data['act']=='edit'){
					$res=M('goods_attr')->where(['goods_attr_id'=>$data['id']])->save($data);
				}elseif($data['act']=='del'){
					$res=M('goods_attr')->where(['goods_attr_id'=>$data['id']])->delete();
				}
			}else{
				if($data['act']=='add'){
					$res=M('goods_attr')->add($data);
				}
			}
			if($res){
				return json_encode(array('status'=>1,'msg'=>'操作成功'));
			}else{
				return json_encode(array('status'=>0,'msg'=>'操作失败'));
			}
		}
	}
	/*
	 * 保养类型列表
	 */
	public function cate2List(){
		$count=M('article_cat2')->count();
		$Page = new Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page->show();// 分页显示输出
		$data=M('article_cat2')->order('cat_id asc')->field('cat_id,cat_name,show_in_nav,sort_order,content')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('count',$count);
		$this->assign('pager',$Page);
		$this->assign('show',$show);
		$this->assign('data',$data);
		return $this->fetch();
	}
	//增加，编辑保养类型
	public function addEditate(){
		if(IS_GET){
			$cat_id=I('get.cat_id');
			$act=I('get.act');
			if($act=='del'){
               $res=M('article_cat2')->where(['cat_id'=>$cat_id])->delete();
				if($res){
					return $this->success('删除成功',U('admin/Topic/cate2List'));
				}else{
					return $this->error('删除失败');
				}
			}elseif($act=='edit'){
                $data=M('article_cat2')->where(['cat_id'=>$cat_id])->field('cat_id,cat_name,sort_order,content')->find();
				$this->assign('data',$data);
			}
			$this->assign('act',$act);
			return $this->fetch();
		}else{
			$postData=I('post.');
			$postData['show_in_nav']=1;
				if($postData['act']=='edit'){
					$res=M('article_cat2')->where(['cat_id'=>$postData['cat_id']])->save($postData);
				}elseif($postData['act']=='add'){
					$res=M('article_cat2')->add($postData);
			    }
			if($res){
				return json_encode(array('status'=>1,'msg'=>'操作成功'));
			}else{
				return json_encode(array('status'=>0,'msg'=>'操作失败'));
			}
		}

	}

}