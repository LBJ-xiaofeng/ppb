<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://#
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ============================================================================
 * Author: 当燃      
 * Date: 2015-09-09
 */
namespace app\admin\controller;

use think\Page;
use app\admin\logic\ArticleCatLogic;

class Article extends Base {

    public function categoryList(){
        $ArticleCat = new ArticleCatLogic(); 
        $cat_list = $ArticleCat->article_cat_list(0, 0, false);
        $this->assign('cat_list',$cat_list);
        return $this->fetch('categoryList');
    }

    public function category()
    {
        $ArticleCat = new ArticleCatLogic();
        $act = I('get.act', 'add');
        $cat_id = I('get.cat_id/d');
        $parent_id = I('get.parent_id/d');
        if ($cat_id) {
            $cat_info = M('article_cat')->where('cat_id=' . $cat_id)->find();
            $parent_id = $cat_info['parent_id'];
            $this->assign('cat_info', $cat_info);
        }
        $cats = $ArticleCat->article_cat_list(0, $parent_id, true);
        $this->assign('act', $act);
        $this->assign('cat_select', $cats);
        return $this->fetch();
    }
    
    public function articleList(){
        $Article =  M('Article'); 
        $res = $list = array();
        $p = empty($_REQUEST['p']) ? 1 : $_REQUEST['p'];
        $size = empty($_REQUEST['size']) ? 20 : $_REQUEST['size'];
        
        $where = " 1 = 1 ";
        $keywords = trim(I('keywords'));
        $keywords && $where.=" and title like '%$keywords%' ";
        $cat_id = I('cat_id',0);
        $cat_id && $where.=" and cat_id = $cat_id ";
        $res = $Article->where($where)->order('article_id desc')->page("$p,$size")->select();
        $count = $Article->where($where)->count();// 查询满足要求的总记录数
        $pager = new Page($count,$size);// 实例化分页类 传入总记录数和每页显示的记录数
        //$page = $pager->show();//分页显示输出
        
        $ArticleCat = new ArticleCatLogic();
        $cats = $ArticleCat->article_cat_list(0,0,false);
        if($res){
        	foreach ($res as $val){
        		$val['category'] = $cats[$val['cat_id']]['cat_name'];
        		$val['add_time'] = date('Y-m-d H:i:s',$val['add_time']);        		
        		$list[] = $val;
        	}
        }
        $this->assign('cats',$cats);
        $this->assign('cat_id',$cat_id);
        $this->assign('list',$list);// 赋值数据集
        $this->assign('pager',$pager);// 赋值分页输出        
		return $this->fetch('articleList');
    }

    //常见问题
    public function commonProblemsList(){
        $Article =  M('Article2');
        $res = $list = array();
        $p = empty($_REQUEST['p']) ? 1 : $_REQUEST['p'];
        $size = empty($_REQUEST['size']) ? 20 : $_REQUEST['size'];

        $where = " 1 = 1 ";
        $keywords = trim(I('keywords'));
        $keywords && $where.=" and title like '%$keywords%' ";
        $res = $Article->where($where)->order('article_id desc')->page("$p,$size")->select();
        $count = $Article->where($where)->count();// 查询满足要求的总记录数
        $pager = new Page($count,$size);// 实例化分页类 传入总记录数和每页显示的记录数
        //$page = $pager->show();//分页显示输出

        $ArticleCat = new ArticleCatLogic();
        $cats = $ArticleCat->article_cat_list(0,0,false);
        if($res){
            foreach ($res as $val){
                $val['category'] = $cats[$val['cat_id']]['cat_name'];
                $val['add_time'] = date('Y-m-d H:i:s',$val['add_time']);
                $val['content'] = html_entity_decode($val['content']);
                $list[] = $val;
            }
        }
        $this->assign('cats',$cats);
        $this->assign('list',$list);// 赋值数据集
        $this->assign('pager',$pager);// 赋值分页输出
        return $this->fetch();
    }
    //编辑、增加问题
    public function commonProblem(){
        $act = I('act','add');
        $article_id=I('article_id');
        if($article_id){
            $info = M('article2')->where('article_id='.$article_id)->find();
        }
        $this->assign('act',$act);
        $this->assign('info',$info);
        return $this->fetch();
    }
    //保存及删除
    public function commonProblemsDetail(){
            $id=I('get.article_id');
            $act=I('get.act');
            if($id && $act=='del'){
                $r = M('article2')->where('article_id='.$id)->delete();
            }
            $data = I('post.');
            $data['publish_time'] = time();
            if ($data['act'] == 'add') {
                $data['click'] = mt_rand(1000,1300);
                $data['add_time'] = time();
                $r = M('article2')->add($data);
            } elseif ($data['act'] == 'edit') {
                $r = M('article2')->where('article_id='.$data['article_id'])->save($data);
            }
            if (!$r) {
               return $this->success("操作失败");
            }else{
                return $this->success("操作成功",U('Admin/Article/commonProblemsList'));
            }


    }
    
    public function article(){
        $ArticleCat = new ArticleCatLogic();
 		$act = I('GET.act','add');
        $info = array();
        $info['publish_time'] = time()+3600*24;
        if(I('GET.article_id')){
           $article_id = I('GET.article_id');
           $info = M('article')->where('article_id='.$article_id)->find();
        }
        $cats = $ArticleCat->article_cat_list(0,$info['cat_id']);
        $this->assign('cat_select',$cats);
        $this->assign('act',$act);
        $this->assign('info',$info);
        return $this->fetch();
    }
    
    
    public function categoryHandle()
    {
    	$data = I('post.');

        $result = $this->validate($data, 'ArticleCategory.'.$data['act'], [], true);
        if ($result !== true) {
            $this->ajaxReturn(['status' => 0, 'msg' => '参数错误', 'result' => $result]);
        }
        
        if ($data['act'] == 'add') {
            $r = M('article_cat')->add($data);
        } elseif ($data['act'] == 'edit') {
        	$cat_info = M('article_cat')->where("cat_id",$data['cat_id'])->find();
        	if($cat_info['cat_type'] == 1 && $data['parent_id'] > 1){
        		$this->ajaxReturn(['status' => -1, 'msg' => '可更改系统预定义分类的上级分类']);
        	}
        	$r = M('article_cat')->where("cat_id",$data['cat_id'])->save($data);
        } elseif ($data['act'] == 'del') {
        	if($data['cat_id']<9){
        		$this->ajaxReturn(['status' => -1, 'msg' => '系统默认分类不得删除']);
        	}
        	if (M('article')->where('cat_id', $data['cat_id'])->count()>0)
        	{
        		$this->ajaxReturn(['status' => -1, 'msg' => '该分类下有文章，不允许删除，请先删除该分类下的文章']);
        	}
        	$r = M('article_cat')->where('cat_id', $data['cat_id'])->delete();
        }
        
        if (!$r) {
            $this->ajaxReturn(['status' => -1, 'msg' => '操作失败']);
        } 
        $this->ajaxReturn(['status' => 1, 'msg' => '操作成功']);
    }
    
    public function aticleHandle()
    {
        $data = I('post.');
        $data['publish_time'] = strtotime($data['publish_time']);
        //$referurl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : U('Admin/Article/articleList');
        
        $result = $this->validate($data, 'Article.'.$data['act'], [], true);
        if ($result !== true) {
            $this->ajaxReturn(['status' => 0, 'msg' => '参数错误', 'result' => $result]);
        }
        
        if ($data['act'] == 'add') {
            $data['click'] = mt_rand(1000,1300);
        	$data['add_time'] = time(); 
            $r = M('article')->add($data);
        } elseif ($data['act'] == 'edit') {
            $r = M('article')->where('article_id='.$data['article_id'])->save($data);
        } elseif ($data['act'] == 'del') {
        	$r = M('article')->where('article_id='.$data['article_id'])->delete(); 	
        }
        
        if (!$r) {
            $this->ajaxReturn(['status' => -1, 'msg' => '操作失败']);
        }
            
        $this->ajaxReturn(['status' => 1, 'msg' => '操作成功']);
    }
    
    
    public function link(){
    	$act = I('GET.act','add');
    	$this->assign('act',$act);
    	$link_id = I('GET.link_id');
    	$link_info = array();
    	if($link_id){
    		$link_info = M('friend_link')->where('link_id='.$link_id)->find();
    		$this->assign('info',$link_info);
    	}
    	return $this->fetch();
    }
    
    public function linkList(){
    	$Ad =  M('friend_link');
        $p = $this->request->param('p');
    	$res = $Ad->order('orderby')->page($p.',10')->select();
    	if($res){
    		foreach ($res as $val){
    			$val['target'] = $val['target']>0 ? '开启' : '关闭';
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
    
    public function linkHandle(){
        $data = I('post.');
    	if($data['act'] == 'del'){
    		$r = M('friend_link')->where(['link_id'=>$data['link_id']])->delete();
    		if($r) exit(json_encode(1));
    	}
        $result = $this->validate($data,'FriendLink.'.$data['act'], [], true);
        if(true !== $result){
            // 验证失败 输出错误信息
            $validate_error = '';
            foreach ($result as $key =>$value){
                $validate_error .=$value.',';
            }
            $this->error($validate_error);
        }
        if($data['act'] == 'add'){
            $r = M('friend_link')->insert($data);
        }
        if($data['act'] == 'edit'){
            $r = M('friend_link')->where('link_id='.$data['link_id'])->save($data);
        }
    	if($r){
    		$this->success("操作成功",U('Admin/Article/linkList'));
    	}else{
            $this->error("操作失败");
    	}
    }

    //首页广告
    public function articleTwo()
    {
        $Article =  M('article_cat_2');
        $data=$Article->where('show_in_nav',0)->order('sort_order asc')->select();
        $this->assign('data',$data);
        return $this->fetch();
    }

    //查看广告
    public function addetail(){
        if(IS_POST){
            $data=I('post.');
            $id=I('post.cat_id');
//            print_r($data);die;
            $res=M('article_cat_2')->where(['cat_id'=>$id])->save($data);
            if($res){
                return $this->success('编辑成功！！！',U('Article/articleTwo'));
            }else{
                return $this->error('编辑失败，请重试！！！');
            }
        }else{
            $id=I('cat_id');
            $data=M('article_cat_2')->where(['cat_id'=>$id])->find();
            $this->assign('info',$data);
            return $this->fetch();
        }

    }
        //客户反馈
    public function opinionList(){
        $model=M('feedback');
        $count=$model->count();
        $Page = new Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $data2=$model->field('msg_id,user_name,msg_content,msg_time,mobile')->order('msg_id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($data2 as $k=>$v){
            $data2[$k]['msg_time']=date('Y-m-d H:i:s',$v['msg_time']);
        }
        $show = $Page->show();// 分页显示输出
        $this->assign('page',$Page);
        $this->assign('show',$show);
        $this->assign('data',$data2);
        return $this->fetch();
    }
    //删除反馈
    public function opinionDel(){
        $res=M('feedback')->where(['msg_id'=>I('id')])->delete();
        if($res){
            return json_encode(1);
        }else{
            return json_encode(0);
        }
    }
}