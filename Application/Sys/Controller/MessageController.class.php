<?php

namespace Sys\Controller;

use Think\Controller;

class MessageController extends SysadminController {
	public $nowpage;
	public function index() {
		$message = D ( 'Message' );
		$count = $message->count();
		$Page = new \Think\Page($count,$size=15);
		$limit = $page->firstRow.','.$Page->listRows;
		$volist = $message->limit($Page->firstRow. ',' . $Page->listRows)->order("msg_id desc")->select();
		$this->pager_bar = $Page->show();
		$this->assign('result',$volist);
		$this->assign('count',$count);
		$this->display();
	}
	public function flag($id) {
		$message = D ( 'Message' );
		$id = 'msg_id=' . $id;
		$data ['msg_flag'] = '1';
		$data ['msg_date'] = date ( "Y-m-d" );
		$info = $message->where ( "$id" )->setField ( $data );
		$now = $_GET ['now'];
		if ($info) {
			$this->success ( '发布成功', U ( "Sys/message/index?p=$now" ) );
		} else {
			$this->error ( '发布失败', U ( 'Sys/Message/index' ) );
		}
	}
/* 	public function isshow($id, $isshow) {
		$message = D ( 'Message' );
		$now = $_GET ['now'];
		$info = $message->field('reply_descript')->select($id);
		$data['msg_id'] = $id;
		if(empty($info[0]['reply_descript'])){
			$data['msg_flag'] = 0;
		}else{
			$data['msg_flag'] = 1;
		}
		$message->save($data);
		//$this->redirect ( "Message/index?p=$now" );
	} */
	public function like(){
		$like = $_GET['text'];
		$map['msg_descript'] = array('like',"%$like%");
		$result = D('Message')->where($map)->select();
		$this->assign('result',$result);
		$this->display('Message/index');
		
	}
	
	public function noreply(){
		$result = D('Message')->where("reply_descript is null or reply_descript = ''")->select();
		$this->assign('result',$result);
		$this->display('Message/index');
	}
	public function reply($id) {
		$message = D ( 'Message' );
		$this->assign ( 'id', $id );
		if (! empty ( $_POST )) {
			$data ['msg_id'] = $id;
			$data['msg_descript'] = $_POST['msg_descript'];
			$data ['reply_descript'] = $_POST ['reply_descript'];
			$data ['msg_date'] = date ( "Y-m-d H:i:s" );
			$data ['msg_flag'] = 1;
			$info = $message->save ( $data );
			$now = $_GET ['now'];
			if ($info) {
				$this->success ( '回复成功', U ( "Sys/Message/index?p=$now" ) );
			} else {
				$this->error ( '回复失败', U ( "Sys/Message/index?p=$now" ) );
			}
		} else {
			$message = M( 'Message' );
			$result = $message->field ( 'msg_descript,reply_descript' )->select ( $id );
			$this->assign ( 'result', $result);
			$this->display ();
		}
	}
	
	function delete(){
		if(!empty($_GET['id'])){
			$id = $_GET['id'];
			D('Message')->where("msg_id=".$id)->delete();
			$this->redirect('Message/index');
		}
		elseif(empty($_POST)){
			$this->redirect('Message/index',array(),1,'至少选择一项！ 1秒后返回！');
		}else{
			$id= implode(",",$_POST['id']);     //将id数组用逗号分隔为字符串
			D('Message')->where("msg_id in ($id)")->delete();
			$this->redirect('Message/index');
		}
	}
	function page($result, $num = 10) {
		import ( 'Org.Util.Page' ); // 导入分页类
		$count = $result->count (); // 查询满足要求的总记录数 $map表示查询条件
		$Page = new \Page ( $count, $num ); // 实例化分页类 传入总记录数
		$show = $Page->show (); // 分页显示输出
		$list = $result->order ( 'msg_id asc' )->limit ( $Page->firstRow . ',' . $Page->listRows )->select ();
		
		$data ['now'] = $Page->nowPage; // 第几页
		$data ['list'] = $list; // 赋值数据集
		$data ['page'] = $show; // 赋值分页输出
		return $data;
	}
}