<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class ContinuesController extends UserWxController {
    public function index(){
		$this->display();
    }
	
	function given($sgssz='',$userid='',$secret=''){
		echo $GLOBALS["HTTP_RAW_POST_DATA"];
		$this->display();
	}
}