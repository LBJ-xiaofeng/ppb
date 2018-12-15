<?php
namespace app\admin\controller;

use think\Page;
use think\Verify;
use think\Db;
use think\Session;

class Admin extends Base {

    public function index(){
    	$list = array();
    	$keywords = I('keywords/s');
    	if(empty($keywords)){
    		$res = D('admin')->select();
    	}else{
			$res = DB::name('admin')->where('user_name','like','%'.$keywords.'%')->order('admin_id')->select();
    	}
    	$role = D('admin_role')->getField('role_id,role_name');
    	if($res && $role){
    		foreach ($res as $val){
    			$val['role'] =  $role[$val['role_id']];
    			$val['add_time'] = date('Y-m-d H:i:s',$val['add_time']);
    			$list[] = $val;
    		}
    	}
    	$this->assign('list',$list);
        return $this->fetch();
    }
    
    /**
     * 修改管理员密码
     * @return \think\mixed
     */
    public function modify_pwd(){
        $admin_id = I('admin_id/d',0);
        $oldPwd = I('old_pw/s');
        $newPwd = I('new_pw/s');
        $new2Pwd = I('new_pw2/s');
       
        if($admin_id){
            $info = D('admin')->where("admin_id", $admin_id)->find();
            $info['password'] =  "";
            $this->assign('info',$info);
        }
        
         if(IS_POST){
            //修改密码
            $enOldPwd = encrypt($oldPwd);
            $enNewPwd = encrypt($newPwd);
            $admin = M('admin')->where('admin_id' , $admin_id)->find();
            if(!$admin || $admin['password'] != $enOldPwd){
                exit(json_encode(array('status'=>-1,'msg'=>'旧密码不正确')));
            }else if($newPwd != $new2Pwd){
                exit(json_encode(array('status'=>-1,'msg'=>'两次密码不一致')));
            }else{
                $row = M('admin')->where('admin_id' , $admin_id)->save(array('password' => $enNewPwd));
                if($row){
                    exit(json_encode(array('status'=>1,'msg'=>'修改成功')));
                }else{
                    exit(json_encode(array('status'=>-1,'msg'=>'修改失败')));
                }
            }
        }
        return $this->fetch();
    }
    
    public function admin_info(){
    	$admin_id = I('get.admin_id/d',0);
    	if($admin_id){
    		$info = D('admin')->where("admin_id", $admin_id)->find();
			$info['password'] =  "";
    		$this->assign('info',$info);
    	}
    	$act = empty($admin_id) ? 'add' : 'edit';
    	$this->assign('act',$act);
    	$role = D('admin_role')->select();
    	$this->assign('role',$role);
    	return $this->fetch();
    }
    
    public function adminHandle(){
    	$data = I('post.');
    	if(empty($data['password'])){
    		unset($data['password']);
    	}else{
    		$data['password'] = encrypt($data['password']);
    	}
    	if($data['act'] == 'add'){
    		unset($data['admin_id']);    		
    		$data['add_time'] = time();
    		if(D('admin')->where("user_name", $data['user_name'])->count()){
    			$this->error("此用户名已被注册，请更换",U('Admin/Admin/admin_info'));
    		}else{
    			$r = D('admin')->add($data);
    		}
    	}
    	
    	if($data['act'] == 'edit'){
    		$r = D('admin')->where('admin_id', $data['admin_id'])->save($data);
    	}
    	
        if($data['act'] == 'del' && $data['admin_id']>1){
    		$r = D('admin')->where('admin_id', $data['admin_id'])->delete();
    		exit(json_encode(1));
    	}
    	
    	if($r){
    		$this->success("操作成功",U('Admin/Admin/index'));
    	}else{
    		$this->error("操作失败",U('Admin/Admin/index'));
    	}
    }
    
    
    /*
     * 管理员登陆
     */
    public function login(){
        if(session('?admin_id') && session('admin_id')>0){
             $this->error("您已登录",U('Admin/Index/index'));
        }
      
        if(IS_POST){
            $verify = new Verify();
            if (!$verify->check(I('post.vertify'), "admin_login")) {
            	exit(json_encode(array('status'=>0,'msg'=>'验证码错误')));
            }
            $condition['user_name'] = I('post.username/s');
            $condition['password'] = I('post.password/s');
            if(!empty($condition['user_name']) || !empty($condition['password'])){
                $condition['password'] = encrypt($condition['password']);
               	$admin_info = M('admin')->join(PREFIX.'admin_role', PREFIX.'admin.role_id='.PREFIX.'admin_role.role_id','INNER')->where($condition)->find();
                if(is_array($admin_info)){
                    session('admin_id',$admin_info['admin_id']);
                    session('act_list',$admin_info['act_list']);
                    session('role_id',$admin_info['role_id']);
                    M('admin')->where("admin_id = ".$admin_info['admin_id'])->save(array('last_login'=>time(),'last_ip'=>  request()->ip()));
                    session('last_login_time',$admin_info['last_login']);
                    session('last_login_ip',$admin_info['last_ip']);
                    adminLog('后台登录');
                    $url = session('from_url') ? session('from_url') : U('Admin/Index/index');
                    exit(json_encode(array('status'=>1,'url'=>$url)));
                }else{
                    exit(json_encode(array('status'=>0,'msg'=>'账号密码不正确')));
                }
            }else{
                exit(json_encode(array('status'=>0,'msg'=>'请填写账号密码')));
            }
        }
        
       return $this->fetch();
    }
    
    /**
     * 退出登陆
     */
    public function logout(){
        session_unset();
		session::clear();
		cookie(null);
        $this->success("退出成功",U('Admin/Admin/login'));
    }
    //忘记密码
	public function forget_pwd()
	{
		if(IS_POST){
			$data=input('post.');
			$user=M('admin')->where(['user_name'=>$data['user_name'],'email'=>$data['email']])->find();
			if(!$user){
				$this->error("用户名与邮箱不符");
			}
			if(!check_email($data['email'])){
				$this->error("邮箱格式不正确");
			}
			$res=M('admin')->where(['user_name'=>$data['user_name'],'email'=>$data['email']])->setField('password',encrypt($data['pwd1']));
			if($res){
				$this->error("重置密码成功,请前往登录",U('Admin/Admin/login'));
			}else{
				$this->error("重置密码失败，请重试");
			}
		}
		return $this->fetch();
	}
    /**
     * 验证码获取
     */
    public function vertify()
    {
        $config = array(
            'fontSize' => 30,
            'length' => 4,
            'useCurve' => true,
            'useNoise' => false,
        	'reset' => false
        );    
        $Verify = new Verify($config);
        $Verify->entry("admin_login");
        exit();
    }
    
    public function role(){
    	$list = D('admin_role')->order('role_id desc')->select();
    	$this->assign('list',$list);
    	return $this->fetch();
    }
    
    public function role_info(){
    	$role_id = I('get.role_id/d');
    	$detail = array();
    	if($role_id){
    		$detail = M('admin_role')->where("role_id",$role_id)->find();
    		$detail['act_list'] = explode(',', $detail['act_list']);
    		$this->assign('detail',$detail);
    	}
		$right = M('system_menu')->where('is_del',0)->order('id')->select();
		foreach ($right as $val){
			if(!empty($detail)){
				$val['enable'] = in_array($val['id'], $detail['act_list']);
			}
			$modules[$val['group']][] = $val;
		}
		//权限组
		$group  = array('system'=>'系统设置','content'=>'内容管理','goods'=>'样例中心','member'=>'会员中心',
             'order'=>'订单中心','count'=>'统计报表'
     	);
		$this->assign('group',$group);
		$this->assign('modules',$modules);
    	return $this->fetch();
    }
    
    public function roleSave(){
    	$data = I('post.');
    	$res = $data['data'];
    	$res['act_list'] = is_array($data['right']) ? implode(',', $data['right']) : '';
        if(empty($res['act_list']))
            $this->error("请选择权限!");        
    	if(empty($data['role_id'])){
			$admin_role = Db::name('admin_role')->where(['role_name'=>$res['role_name']])->find();
			if($admin_role){
				$this->error("已存在相同的角色名称!");
			}else{
				$r = D('admin_role')->add($res);
			}
    	}else{
			$admin_role = Db::name('admin_role')->where(['role_name'=>$res['role_name'],'role_id'=>['<>',$data['role_id']]])->find();
			if($admin_role){
				$this->error("已存在相同的角色名称!");
			}else{
				$r = D('admin_role')->where('role_id', $data['role_id'])->save($res);
			}
    	}
		if($r){
			adminLog('管理角色');
			$this->success("操作成功!",U('Admin/Admin/role'));
		}else{
			$this->error("操作失败!",U('Admin/Admin/role'));
		}
    }
    
    public function roleDel(){
    	$role_id = I('post.role_id/d');
		if(in_array($role_id,explode(',','10,11,12,13,14,15'))){
			exit(json_encode("系统角色，不可删除"));
		}

    	$admin = D('admin')->where('role_id',$role_id)->find();
    	if($admin){
    		exit(json_encode("请先清空所属该角色的管理员"));
    	}else{
    		$d = M('admin_role')->where("role_id", $role_id)->delete();
    		if($d){
    			exit(json_encode(1));
    		}else{
    			exit(json_encode("删除失败"));
    		}
    	}
    }
    
    public function log(){
    	$p = I('p/d',1);
    	$logs = DB::name('admin_log')->alias('l')->join('__ADMIN__ a','a.admin_id =l.admin_id')->order('log_time DESC')->page($p.',20')->select();
    	$this->assign('list',$logs);
    	$count = DB::name('admin_log')->count();
    	$Page = new Page($count,20);
    	$show = $Page->show();
		$this->assign('pager',$Page);
		$this->assign('page',$show);
    	return $this->fetch();
    }


	/**
	 * 供应商列表
	 */
	public function supplier()
	{
		$supplier_count = DB::name('suppliers')->count();
		$page = new Page($supplier_count, 10);
		$show = $page->show();
		$supplier_list = DB::name('suppliers')
				->alias('s')
				->field('s.*,a.admin_id,a.user_name')
				->join('__ADMIN__ a','a.suppliers_id = s.suppliers_id','LEFT')
				->limit($page->firstRow, $page->listRows)
				->select();
		$this->assign('list', $supplier_list);
		$this->assign('page', $show);
		return $this->fetch();
	}

	/**
	 * 供应商资料
	 */
	public function supplier_info()
	{
		$suppliers_id = I('get.suppliers_id/d', 0);
		if ($suppliers_id) {
			$info = DB::name('suppliers')
					->alias('s')
					->field('s.*,a.admin_id,a.user_name')
					->join('__ADMIN__ a','a.suppliers_id = s.suppliers_id','LEFT')
					->where(array('s.suppliers_id' => $suppliers_id))
					->find();
			$this->assign('info', $info);
		}
		$act = empty($suppliers_id) ? 'add' : 'edit';
		$this->assign('act', $act);
		$admin = M('admin')->field('admin_id,user_name')->select();
		$this->assign('admin', $admin);
		return $this->fetch();
	}

	/**
	 * 供应商增删改
	 */
	public function supplierHandle()
	{
		$data = I('post.');
		$suppliers_model = M('suppliers');
		//增
		if ($data['act'] == 'add') {
			unset($data['suppliers_id']);
			$count = $suppliers_model->where("suppliers_name", $data['suppliers_name'])->count();
			if ($count) {
				$this->error("此供应商名称已被注册，请更换", U('Admin/Admin/supplier_info'));
			} else {
				$r = $suppliers_model->insertGetId($data);
				if (!empty($data['admin_id'])) {
					$admin_data['suppliers_id'] = $r;
					M('admin')->where(array('suppliers_id' => $admin_data['suppliers_id']))->save(array('suppliers_id' => 0));
					M('admin')->where(array('admin_id' => $data['admin_id']))->save($admin_data);
				}
			}
		}
		//改
		if ($data['act'] == 'edit' && $data['suppliers_id'] > 0) {
			$r = $suppliers_model->where('suppliers_id',$data['suppliers_id'])->save($data);
			if (!empty($data['admin_id'])) {
				$admin_data['suppliers_id'] = $data['suppliers_id'];
				$suppliers = $suppliers_model->where('suppliers_id',$data['suppliers_id'])->find();
				$admin_data['city_id'] = $suppliers['city_id'];
				$admin_data['province_id'] = $suppliers['province_id'];
				M('admin')->where(array('admin_id' => $data['admin_id']))->save($admin_data);
			}
		}
		//删
		if ($data['act'] == 'del' && $data['suppliers_id'] > 0) {
			$r = $suppliers_model->where('suppliers_id', $data['suppliers_id'])->delete();
			M('admin')->where(array('suppliers_id' => $data['suppliers_id']))->save(array('suppliers_id' => 0));
		}

		if ($r !== false) {
			$this->success("操作成功", U('Admin/Admin/supplier'));
		} else {
			$this->error("操作失败", U('Admin/Admin/supplier'));
		}
	}
	public function adminUser(){
		$list = array();
		$keywords = I('keywords/s');
		if(empty($keywords)){
			$res = M('users')->where(['level'=>1])->order('reg_time desc')->select();
		}else{
			$res =M('users')->where(['level'=>1])->where('mobile','like','%'.$keywords.'%')->order('reg_time desc')->select();
		}
		foreach ($res as $val){
			$val['reg_time'] = date('Y-m-d H:i:s',$val['reg_time']);
			$val['last_login'] = date('Y-m-d H:i:s',$val['last_login']);
			$list[] = $val;
		}
		$this->assign('list',$list);
		return $this->fetch();
	}
	public function adminUser_info(){
		$admin_id = I('get.user_id/d',0);
		if($admin_id){
			$info = D('users')->where("user_id", $admin_id)->find();
			$info['password'] =  "";
			$this->assign('info',$info);
		}
		$act = empty($admin_id) ? 'add' : 'edit';
		$this->assign('act',$act);
		return $this->fetch();
	}

	public function delUser(){
		$data=I('get.');
		$r = D('users')->where('user_id', $data['user_id'])->delete();
		if($r){
			$this->success("操作成功",U('Admin/Admin/adminUser'));
		}else{
			$this->error("操作失败",U('Admin/Admin/adminUser'));
		}
	}

	public function adminUserHandle(){
		$data = I('post.');
		if(!check_mobile($data['mobile'])){
			$this->error("手机号码格式不正确",U('Admin/Admin/adminUser_info'));
		}
		if(empty($data['password'])){
			unset($data['password']);
		}else{
			$data['password'] = md5($data['password']);
		}
		if($data['act'] == 'add'){
			$data['reg_time'] = time();
			$data['level'] = 1;
			if(D('users')->where("mobile", $data['mobile'])->count()){
				$this->error("此用户名已被注册，请更换",U('Admin/Admin/adminUser_info'));
			}else{
				$r = D('users')->add($data);
			}
		}
		if($data['act'] == 'edit'){
			$r = D('users')->where('user_id', $data['user_id'])->save($data);
		}
		if($r){
			$this->success("操作成功",U('Admin/Admin/adminUser'));
		}else{
			$this->error("操作失败",U('Admin/Admin/adminUser'));
		}
	}
}