<?php
namespace Sys\Controller;
use Think\Controller;
class ModuleController extends SysadminController {
    public function index() {
		$this->display();
	}

	public function add() {
		$From=D("authority");
		$where['leve']='1';
		$where['pid']='0';
		$left_list=$From->where($where)->order("order1")->select();
		foreach($left_list as $key => $val){
			$where="leve='2' and pid=".$val['id'];
			$left_list[$key]['voo']=$From->where("$where")->select();
		}
		$this->assign("lm_list",$left_list);
		$this->display();
	}
	
	function insert(){
		$myselect=$_POST['myselect'];
		$From=D("authority");
		if(!$From->create()) {
			header("Content-Type:text/html; charset=utf-8");
			$this->error('创建信息数据库信息失败！');
		}
		$arr=explode("_",$myselect);
		$From->pid=$arr[0];
		if($arr[1]==0)
		   $From->leve='1';
		elseif($arr[1]==1)
		   $From->leve='2';
		elseif($arr[1]==2)
		   $From->leve='3';
		if(isset($_POST['id']) && empty($_POST['id'])){
			if(false !==$From->add()) {
				//$this->assign('jumpUrl','/Module/add');
				$this->success('数据添加成功！',U('Module/add'),'3');
			}else{
				$this->error('数据写入错误');
			}
		}else{
		}
	}
}