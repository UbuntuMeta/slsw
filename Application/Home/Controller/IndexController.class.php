<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class IndexController extends UserWxController {
    public function index($id='',$url=''){
		if($id){
			$From=D("Weixin_menu");
			$vo=$From->field("title")->find($id);
			self::requestdata('',$vo['title']);
		}else{
			redirect(U('noindex'));
		}
		$this->assign("url",$url);
		$this->display();
    }
	
	public function showurl($url=''){
		//file_put_contents(dirname(__FILE__) . "/request.txt", $url);
		$str=explode("~",$url);
		if($str[0]){
			$From=D("Weixin_menu");
			$vo=$From->field("title")->find($str[0]);
			self::requestdata('点击菜单',$vo['title']);
		}
		$this->assign("url","http://".base64_decode($str[1]));
		$this->display();
    }
	
	public function noindex(){
		$this->display();
	}
	function requestdata($type='点击菜单',$methods=''){
		$data ['year'] = date ( 'Y' );	
		$data ['month'] = date ( 'm' );		
		$data ['day'] = date ( 'd' );
		$data ['type'] = $type;	
		$data ['methods'] = $methods;		
		$data ['wecha_id'] = $_SESSION['wecha_id'];
		$data ['uid'] = $_SESSION['member_id'];
		$data ['time'] = time ();
		$mysql = M ( 'Requestdata' );	
		$mysql->add ( $data );	
//		$check = $mysql->field ( 'id' )->where ( $data )->find ();		
//		if ($check == false) {			
//			$data ['time'] = time ();			
//			$data ['typecount'] = 1;			
//			$mysql->add ( $data );
//		} else {
//			$mysql->where ( $data )->setInc ("typecount");
//		}
	}
}