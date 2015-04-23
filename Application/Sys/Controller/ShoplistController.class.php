<?php
namespace Sys\Controller;
use Think\Controller;
class ShoplistController extends SysadminController {
    public function index(){
		$Form=M("Shop");
		$count = $Form->count();    //计算总数
		$p = new \Think\Page($count, $size=15);//import("@.ORG.Pagewx"); //导入分页类
		$list=$Form->field("id,name,picurl,type,tel,address")->limit($p->firstRow.','.$p->listRows)->order("id desc")->select();
		$page = $p->show ();
		$this->assign ( "list", $list);
		$this->assign ( "page", $page );
		$this->display();
    }
	
	function add($id=''){
		if($id){
			$vo=M("Shop")->find($id);
			$this->assign ( "vo", $vo);
		}
		$this->display();
	}
	
	function insert($id=''){
		$From=D("Shop");
		if(!$From->create()) {
			header("Content-Type:text/html; charset=utf-8");
			$this->error('创建信息数据库信息失败！');
		}
		if(isset($id) && empty($id)){
			if(false !==$From->add()) {
				$this->success('数据添加成功！',U('Shoplist/index'),'3');
			}else{
				$this->error('数据写入错误');
			}
		}else{
			if(false !==$From->save()) {
				//$this->assign('jumpUrl','/Menu/add');
				$this->success('数据修改成功！',U('Shoplist/index'),'3');
			}else{
				$this->error('数据修改错误');
			}
		}
	}
}