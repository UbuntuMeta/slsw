<?php
	namespace Sys\Controller;
	use Think\Controller;
	
	class UserController extends SysadminController{
		public function userindex(){
			$model = D('user');
			$where['status'] = array('in','1,2');
			$count = $model->where($where)->count();
			$Page = new \Think\Page($count,$size=20);
			$limit = $page->firstRow.','.$Page->listRows;
			$volist = $model->where($where)->limit($Page->firstRow. ',' . $Page->listRows)->order("user_id desc")->select();
			$this->pager_bar = $Page->show();
			$this->assign('volist',$volist);
			$this->assign('count',$count);
			$this->display();
		}
		
		public function companyindex(){
			$model = D('company');
			$where['status'] = array('in','1,2');
			$count = $model->where($where)->count();
			$Page = new \Think\Page($count,$size=20);
			$limit = $page->firstRow.','.$Page->listRows;
			$volist = $model->where($where)->limit($Page->firstRow. ',' . $Page->listRows)->order("company_id desc")->select();
			$this->pager_bar = $Page->show();
			$this->assign('volist',$volist);
			$this->assign('count',$count);
			$this->display();
		}
		public function userindex1(){
			$model = D('user');
			$where['status'] = 3;
			$count = $model->where($where)->count();
			$Page = new \Think\Page($count,$size=20);
			$limit = $page->firstRow.','.$Page->listRows;
			$volist = $model->where($where)->limit($Page->firstRow. ',' . $Page->listRows)->order("user_id desc")->select();
			$this->pager_bar = $Page->show();
			$this->assign('volist',$volist);
			$this->assign('count',$count);
			$this->display();
		}
		
		public function companyindex1(){
			$model = D('company');
			$where['status'] = 3;
			$count = $model->where($where)->count();
			$Page = new \Think\Page($count,$size=20);
			$limit = $page->firstRow.','.$Page->listRows;
			$volist = $model->where($where)->limit($Page->firstRow. ',' . $Page->listRows)->order("company_id desc")->select();
			$this->pager_bar = $Page->show();
			$this->assign('volist',$volist);
			$this->assign('count',$count);
			$this->display();
		}
		
		public function updatepass(){
			$this->display();
		}
		public function updatepassdo(){
			$pass = $_POST['password'];
			$pass1 = $_POST['password1'];
			$pass2 = $_POST['password2'];
			if($pass1 != $pass2){
				$this->error('密码和确认密码不一样');
			}
			
			$model = M('sysadmin');
			$where['code'] = 'admin';
			$where['pwd'] = md5($pass);
			$user = $model->where($where)->find();
			if($user){
				$data['pwd'] = md5($pass1);
				if($model->where($where)->save($data)){
					$this->success('修改成功');
				}
			
			}else {
				$this->error('修改失败');
			}
		}
	
	}


?>