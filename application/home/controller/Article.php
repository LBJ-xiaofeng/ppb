<?php
/**
 * tpshop
 * ============================================================================
 * * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://#
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 采用TP5助手函数可实现单字母函数M D U等,也可db::name方式,可双向兼容
 * ============================================================================
 * $Author: IT宇宙人 2015-08-10 $
 */
namespace app\home\controller;
use think\Db;

class Article extends Base {
    const CHECK_TOKEN='asDFgtRewq';  //设置验证token
    public function bookList()
    {
        header("Content-type: text/html; charset=utf-8");
        $token=I('get.token');
        if($token !=self::CHECK_TOKEN){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        $article_id = I('article_id/d',1);
        $article = Db::name('article')->where("article_id", $article_id)->field('article_id,cat_id,title,content,thumb,click,add_time')->find();
        if($article){
            $parent = Db::name('article_cat')->where("cat_id",$article['cat_id'])->field('cat_name')->find();
            $this->assign('cat_name',$parent['cat_name']);
            $this->assign('article',$article);
        }
        $article['content']=html_entity_decode($article['content']);
        $article['add_time']=date('Y-m-d H:i',$article['add_time']);
        $article['thumb']='http://'.$_SERVER['HTTP_HOST'].$article['thumb'];
        $sql='select a.article_id,a.title,a.content,a.add_time,a.thumb,a.click,b.cat_id,b.cat_name from tp_article as a LEFT  JOIN tp_article_cat as b on a.cat_id=b.cat_id WHERE a.is_open=1 and b.show_in_nav=1 order by b.sort_order';
        $data=M('article')->query($sql);
        foreach($data as $k=>$v){
            $data[$k]['content']=html_entity_decode($v['content']);
            $data[$k]['add_time']=date('Y-m-d H:i',$v['add_time']);
            $data[$k]['thumb']='http://'.$_SERVER['HTTP_HOST'].$v['thumb'];
        }
        if($data){
            foreach($data as $val){
                $cat_path =  M('article_cat')->where(['cat_id'=>$val['cat_id']])->field('cat_name')->find();
                $datasd[$cat_path['cat_name']][] = $val;
            }
        }
        $datas['nav']=$parent['cat_name'];
        $datas['article']=$article;
        $datas['navarticle']=$datasd;
        if($datas){
            return json_encode(array('status'=>1,'msg'=>'文章获取成功','data'=>$datas));
        }else{
            return json_encode(array('status'=>0,'msg'=>'文章获取失败','data'=>''));
        }
    }
 public function index(){
        $article_id = I('article_id/d',38);
    	$article = Db::name('article')->where("article_id", $article_id)->find();
    	$this->assign('article',$article);
        return $this->fetch();
    }
 
    /**
     * 文章内列表页
     */
    public function articleList(){
        $article_cat = M('ArticleCat')->where("parent_id  = 0")->select();
        print_r($article_cat);die;
        $this->assign('article_cat',$article_cat);
        return $this->fetch();
    }    
    /**
     * 文章内容页
     */
    public function detail(){
        $tp_config = M('config')->where('name="store_name" or name="store_keyword" or name="store_desc" or name="store_logo"')->select();
        $this->assign('tpshop_config', $tp_config);
    	$article_id = I('article_id/d',1);
    	$article = Db::name('article')->where("article_id", $article_id)->find();
    	if($article){
    		$parent = Db::name('article_cat')->where("cat_id",$article['cat_id'])->find();
    		$this->assign('cat_name',$parent['cat_name']);
    		$this->assign('article',$article);
    	}
        $sql='select a.article_id,a.title,a.content,a.add_time,a.thumb,a.click,b.cat_id,b.cat_name from tp_article as a LEFT  JOIN tp_article_cat as b on a.cat_id=b.cat_id WHERE a.is_open=1 and b.show_in_nav=1 order by b.sort_order';
        $data=M('article')->query($sql);
        if($data){
            foreach($data as $val){
                $cat_path =  M('article_cat')->where(['cat_id'=>$val['cat_id']])->field('cat_name')->find();
                $datas[$cat_path['cat_name']][] = $val;
            }
        }
        $this->assign('data',$datas);
        return $this->fetch();
    }

    //客户点击增加点击量
    public function clickArticle()
    {
        $token=I('get.token');
        if($token !=self::CHECK_TOKEN){
            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
        }
        $article_id = I('article_id/d',1);
        $res=M('article')->where(['article_id'=>$article_id])->field('click')->find();
        $data['click']=$res['click']+1;
        $result=M('article')->where(['article_id'=>$article_id])->save($data);
        if($result){
            return json_encode(array('status'=>1,'msg'=>'点击增加成功','data'=>''));
        }else{
            return json_encode(array('status'=>0,'msg'=>'点击增加失败','data'=>''));
        }
    }

    //广告
//    public function getad(){
//        $token=I('get.token');
//        if($token !=self::CHECK_TOKEN){
//            return json_encode(array('status'=>0,'data'=>'','msg'=>'非本站用户，访问无效'));
//        }
//        $type=I('get.type');
//        $model=M('article_cat_2');
//        if($type==0){
//            $data=$model->where(['cat_desc'=>0])->select();//关于
//        }elseif($type==1){
//            $data=$model->where(['cat_desc'=>1])->select();//首页
//        }
//        foreach($data as $k=>$v){
//            $data[$k]['content']=html_entity_decode($v['content']);
//        }
//        if($data){
//            return json_encode(array('status'=>1,'msg'=>'获取成功','data'=>$data));
//        }else{
//            return json_encode(array('status'=>0,'msg'=>'获取失败','data'=>''));
//        }
//    }
//详情
    public function getdetail(){
        $id=I('get.cat_id');
        $model=M('article_cat_2');
            $data=$model->where(['cat_id'=>$id])->find();//关于
            $data['content']=html_entity_decode($data['content']);
            $data['img']='http://'.$_SERVER['HTTP_HOST'].$data['img'];
        if($data){
            return json_encode(array('status'=>1,'msg'=>'获取成功','data'=>$data));
        }else{
            return json_encode(array('status'=>0,'msg'=>'获取失败','data'=>''));
        }
    }
    //开通城市
    public function ktCity(){
        $province=M('ktcity')->select();
        $meun=$this->list_to_tree($province,'id','parent_id','child',0);
        if($meun){
            return json_encode(array('status'=>1,'msg'=>'获取成功','data'=>$meun));
        }else{
            return json_encode(array('status'=>0,'msg'=>'暂无开通城市','data'=>''));
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

}