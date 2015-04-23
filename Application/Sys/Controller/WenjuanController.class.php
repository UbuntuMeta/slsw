<?php
namespace Sys\Controller;
use Think\Controller;
class WenjuanController extends Controller {
	function index(){

		$Form=M("Wenjuan");
		$count = $Form->count();    //计算总数
		$p = new \Think\Page($count, $size=1);//import("@.ORG.Pagewx"); //导入分页类
		$vo=$Form->limit($p->firstRow.','.$p->listRows)->order("id desc")->select();
		$page = $p->show ();
		$this->assign ( "vo", $vo[0] );
		$this->assign ( "page", $page );
		$this->display();
	}
}
?>