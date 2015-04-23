<?php
namespace Home\Controller;
use Think\Controller;
class MessageController extends Controller {
	public function add(){
		if(empty($_POST)){
			$this->display();
		}else{
			$message = D('message');
			$data['msg_descript'] = I('post.content');
			$data['msg_phone'] = I('post.phone');
			$data['msg_startdate'] = date("Y-m-d H:i:s");
			$info = $message->add($data);
			if($info){
				$this->redirect('Message/index');
			}else{
				$this->redirect('Message/index');
			}
		}
	}
	
	public function index(){
		$msg = D('Message');
		if(!empty($_POST)){
			$phone = I('post.phone');
			$where = 'msg_phone = ' . $phone;
			$result['msg'] = $msg->order('msg_startdate desc')->where($where)->select();
			$this->assign('result_msg',$result['msg']);
		}
		$this->display();
	}
}