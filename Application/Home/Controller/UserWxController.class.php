<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class UserWxController extends Controller {
    public function _initialize(){
//$agent = $_SERVER['HTTP_USER_AGENT']; 
//		if(!strpos($agent,"MicroMessenger")) {
//		//	echo '此功能只能在微信浏览器中使用';exit;
//		}
		if($_SESSION['wecha_id']){
			$where['wecha_id']=$_SESSION['wecha_id'];
			$where['status'] = 1;
			$From = D("user");
			$user = $From->field('wecha_id,user_id,user_phone,user_username,user_sfz,tax_class')->where($where)->find();
			if($user){
				$_SESSION['user_id']=$user['user_id'];
			}else {
				$From1 = D('company');
				$com = $From1->field('company_id','company_name','company_phone','company_tax','company_class','tax_class')->where($where)->find();
				if($com){
					$_SESSION['user_id']=$com['company_id'];
				}
			}
		}
    }
}