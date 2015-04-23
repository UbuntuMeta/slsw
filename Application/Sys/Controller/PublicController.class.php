<?php
namespace Sys\Controller;
use Think\Controller;
class PublicController extends Controller {
    public function index(){
		$From=D("rm_vip_info");
		$vo=$From->select();
		dump($vo);
        $this->show("欢迎来到双流地税");
    }
	
	public function login(){
		$this->display();
    }
	
	public function verify(){
        $config =    array(
           'fontSize'    =>    15,    // 验证码字体大小
           'length'      =>    4,     // 验证码位数
           //'useNoise'    =>    false, // 关闭验证码杂点
		   //'useImgBg' => true,
		   //'useZh' => true,
        );
        $Verify =     new \Think\Verify($config);
        $Verify->entry();
    }
	
	function CheckLogin($user='',$password='',$verify=''){
		if(empty($user)) {
			$this->error('帐号错误！');
		}elseif (empty($password)){
			$this->error('密码必须！');
		}//elseif (''===trim($verify)){
			//$this->error('验证码必须！');
		//}
       // if(!check_verify($verify)) {
       //     $this->error('验证码错误！');
        //}
		$logintype="系统用户登录";
        $Store = M('sysadmin');
		$data = $Store->create();
        $map['code'] = $user;
		$map['pwd'] = md5($password);
		$pass = $Store->where($map)->find();
        if($pass){
            $_SESSION[C('SYS_AUTH_ID')]=$pass['id'];
			$_SESSION["codename"]=$pass['code'];
            $this->success("登录成功",PHP_FILE."/Sys",3);
        }else{
            $this->error("此登录帐号或密码错误！");
         }
	}
	
	// 用户登出
    public function logout()
    {
        if(isset($_SESSION[C('SYS_AUTH_ID')])) {
			unset($_SESSION[C('SYS_AUTH_ID')]);
			unset($_SESSION["codename"]);
            $this->success('登出成功！');
        }else {
            $this->error('已经登出！');
        }
    }
}