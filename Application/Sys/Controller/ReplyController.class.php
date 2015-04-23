<?php

namespace Sys\Controller;

use Think\Controller;

class ReplyController extends SysadminController {
	public function index() {
		$Reply = D ( 'Reply' );
		import ( 'Org.Util.Page' ); // 导入分页类
		$count = $Reply->join ( 'left join t_message on t_reply.msg_id = t_message.msg_id' )->count ();
		$Page = new \Page ( $count, 10 ); // 实例化分页类 传入总记录数
		$show = $Page->show (); // 分页显示输出
		$list = $Reply->join ( 'left join t_message on t_reply.msg_id = t_message.msg_id' )->limit ( $Page->firstRow . ',' . $Page->listRows )->select ();
		$data ['list'] = $list; // 赋值数据集
		$data ['page'] = $show; // 赋值分页输出
		$data ['now'] = $Page->nowPage;
		$this->assign ( 'result', $data ['list'] );
		$this->assign ( 'page', $data ['page'] );
		$this->assign ( 'now', $data ['now'] );
		$this->display ();
	}
	public function delete($id) {
		$Reply = D ( 'Reply' );
		$info = $Reply->delete ( $id );
		$now = $_GET ['now'];
		if ($info) {
			$this->success ( '删除成功', U ( "Sys/Reply/index?p=$now" ) );
		} else {
			$this->error ( '删除失败', U ( "Sys/Reply/index?p=$now" ) );
		}
	}
	public function updata($id) {
		$now = $_GET ['now'];
		$Reply = D ( 'Reply' );
		if (empty ( $_POST )) {
			$result = $Reply->join ( 'left join t_message on t_reply.msg_id = t_message.msg_id' )->select ($id);
			$this->assign ( 'id', $id );
			$this->assign ( 'result', $result );
			$this->display ();
		} else {
			$data ['reply_id'] = $id;
			$data ['reply_descript'] = $_POST ['reply'];
			$info = D ( 'Reply' )->save ( $data );
			if ($info) {
				$this->success ( '删除成功', U ( "Sys/Reply/index?p=$now" ) );
			} else {
				$this->error ( '删除失败', U ( "Sys/Reply/index?p=$now" ) );
			}
		}
	}
	public function flag($id, $flag) {
		$Reply = D ( 'Reply' );
		$now = $_GET ['now'];
		if ($flag == 1) {
			$data ['reply_id'] = $id;
			$data ['reply_flag'] = '0';
			$Reply->save ( $data );
		} else {
			$data ['reply_id'] = $id;
			$data ['reply_flag'] = '1';
			$Reply->save ( $data );
		}
		$this->redirect ("Reply/index?p=$now");
	}
}