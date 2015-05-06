<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends UserWxController {

	public function usershow(){
		$wecha_id = $_SESSION['wecha_id'];
		$this->assign('wecha_id',$wecha_id);
		$this->display();
	}
	public function register(){
		$wecha_id = $_POST['wecha_id'];
		$user_username = $_POST['user_username'];
		$user_name = $_POST['user_name'];
		$user_phone = $_POST['user_phone'];
		$user_pass = $_POST['user_pass'];
		$user_sfz = $_POST['user_sfz'];
		$user_tax = $_POST['taxclass'];
        $email = $_POST['user_email'];

        if(empty($user_username)){
			$data["content"] = "用户名不能为空";
			$this->ajaxReturn($data);
		}
		if(empty($user_phone)){
			$data["content"] = "手机号不能为空";
			$this->ajaxReturn($data);
		}
		if(empty($user_pass)){
			$data["content"] = "密码不能为空";
			$this->ajaxReturn($data);
		}
		if(empty($user_sfz)){
			$data["content"] = "身份证不能为空";
			$this->ajaxReturn($data);
		}
		if(self::checkUsername($user_username)){
			$data["content"] = "用户名已经注册";
			$this->ajaxReturn($data);
		}
		if(self::checkWecha($wecha_id)){
			$data["content"] = "微信已经注册，只需再次点击中心";
			$this->ajaxReturn($data);
		}
        if(empty($email)){
            $data["content"] = "email不能为空!";
            $this->ajaxReturn($data);
        }
		$data['user_username'] = $user_username;
		$data['user_name'] = $user_name;
		$data['user_phone'] = $user_phone;
		$data['user_pass'] = md5($user_pass);
		$data['user_sfz'] = $user_sfz;
		$data['tax_class'] = $user_tax;
		$data['user_date'] = date('Y-m-d H:i:s',time());
		$data['wecha_id'] = $wecha_id;
		$data['status'] = 1;
        $data['user_email'] = $email;
		$model = M('user');
		$_SESSION['wecha_id'] = $wecha_id;
		
		if($model->add($data)){
		
		$groupid = 112;
		$a = self::sendgroup($groupid,$wecha_id);
		$data['status'] = 1;
		$data['content'] = "注册成功";
		$data['url']=U('Member/user',array('wecha_id'=>$wecha_id,'token'=>$token));
		$this->ajaxReturn($data);
		}else{
		$data['content'] = "注册失败。1秒后进入";
		$this->ajaxReturn($data);
		}
	}
	
	
	public function first($token='',$wecha_id=''){

		if($wecha_id){
			$where['wecha_id']=$wecha_id;
			$where['status'] = 1;
			$From = D("user");
			$user = $From->field('wecha_id,user_id,user_name,user_phone,user_username,user_sfz,tax_class')->where($where)->find();
			if($user){
				$_SESSION['user_id']=$user['user_id'];
			}else{
				$From1 = D('company');
				$com = $From1->field('company_id','company_name','company_phone','company_tax','company_class','tax_class')->where($where)->find();
				if($com){
					$_SESSION['user_id']=$user['user_id'];
				}else{
					unset($_SESSION['user_id']);
				}
			}
		} else {
            if ($_GET['new_reg']) {
                $round_str = 'ASDFGHJKLQWERTYUIOPZXCVBNMasdfghjklqwertyuiopzxcvbnm1234567890_';
                $new_str = substr(str_shuffle($round_str),0 , 23);
                $wecha_id = 'otjA7' . $new_str;
                $_SESSION['wecha_id'] = $wecha_id;
                $_GET['wecha_id'] = $_SESSION['wecha_id'];
            }
        }

		if($_SESSION['user_id']) {
			//redirect(PHP_FILE.C('SYSADMIN_AUTH_GATEWAY'));
			redirect(U('Member/index',array('token'=>$token,'wecha_id'=>$wecha_id)));
		}
		$this->assign("wecha_id",$_SESSION['wecha_id']);	
		$wecha_id = $_GET['wecha_id'];
		$_SESSION['wecha_id']=$wecha_id;
		$this->display();
	}
	public function companyshow(){
		$wecha_id = $_SESSION['wecha_id'];
		$this->assign('wecha_id',$wecha_id);
		$this->display();
	}
	public function companyshow1(){
		$wecha_id = $_SESSION['wecha_id'];
		$this->assign('wecha_id',$wecha_id);
		$this->display();
	}
	//企业注册
	public function companyregister(){
		$wecha_id = $_POST['wecha_id'];
		$company_username = $_POST['company_username'];
		$company_name = $_POST['company_name'];
		$company_phone = $_POST['company_phone'];
		$company_pass = $_POST['company_pass'];
		$company_tax = $_POST['company_tax'];
		$tax_class = $_POST['tax_class'];
		$company_class = $_POST['company_class'];
        $company_email = $_POST['company_email'];
		if(empty($company_name)){
			$data["content"]="公司名不能为空！";
			$this->ajaxReturn($data);
		}
		if(empty($company_name)){
			$data["content"]="公司名不能为空！";
			$this->ajaxReturn($data);
		}
		if(empty($company_pass)){
			$data["content"]="密码不能为空！";
			$this->ajaxReturn($data);
		}
		if(empty($company_tax)){
			$data["content"]="税务登记证不能为空！";
			$this->ajaxReturn($data);
		}
		if(empty($tax_class)){
			$data["content"]="税务所必须选择！";
			$this->ajaxReturn($data);
		}
		if(empty($company_class)){
			$data["content"]="公司类型必须选择！";
			$this->ajaxReturn($data);
		}
		if(self::checkUsername($company_username)){
			$data["content"]="手机号码已经注册！";
			$this->ajaxReturn($data);
		}
		if(empty($wecha_id)){
			$wecha_id = $_SESSION['wecha_id'];
		}
		if(self::checkWecha($wecha_id)){
			$data["content"] = "微信已经注册，只需绑定即可";
			$this->ajaxReturn($data);
		}
        if (empty($company_email)) {
            $data["content"] = "email不能为空!";
            $this->ajaxReturn($data);
        }
		
		$data['company_name'] = $company_name;
		$data['company_phone'] = $company_phone;
		$data['company_pass'] = md5($company_pass);
		$data['company_tax'] = $company_tax;
		$data['tax_class'] = $tax_class;
		$data['company_class'] = $company_class;
		$data['company_username'] = $company_username;
		$data['wecha_id'] = $wecha_id;
		$data['status'] = 1;
		$data['company_date'] = date('Y-m-d H:i:s', time());
        $data['company_email'] = $company_email;
		$model = M('company');
		if($model->add($data)){
		$groupid = self::getgroupid($tax_class);
		$a = self::sendgroup($groupid,$wecha_id);
		$data['content'] = "注册成功。1秒后进入";
		$data['url']=U('Member/company',array('wecha_id'=>$_SESSION['wecha_id'],'token'=>$token));
		$data['status']  = 1;
		$this->ajaxReturn($data);
		}else{
		$data['content'] = "注册失败。1秒后进入";
		$this->ajaxReturn($data);
		}
	}
	//个体工商注册
	public function companyregister1(){
		$wecha_id = $_POST['wecha_id'];
		$company_username = $_POST['company_username'];
		$company_name = $_POST['company_name'];
		$company_phone = $_POST['company_phone'];
		$company_pass = $_POST['company_pass'];
		$company_tax = $_POST['company_tax'];
		$tax_class = $_POST['tax_class'];
        $company_email = $_POST['company_email'];
		
		if(empty($company_name)){
			$data["content"]="公司名不能为空！";
			$this->ajaxReturn($data);
		}
		if(empty($company_name)){
			$data["content"]="公司名不能为空！";
			$this->ajaxReturn($data);
		}
		if(empty($company_pass)){
			$data["content"]="密码不能为空！";
			$this->ajaxReturn($data);
		}
		if(empty($company_tax)){
			$data["content"]="税务登记证不能为空！";
			$this->ajaxReturn($data);
		}
		if(empty($tax_class)){
			$data["content"]="税务所必须选择！";
			$this->ajaxReturn($data);
		}
		if(self::checkUsername($company_username)){
			$data["content"]="手机号码已经注册！";
			$this->ajaxReturn($data);
		}
		if(empty($wecha_id)){
			$wecha_id = $_SESSION['wecha_id'];
		}
		if(self::checkWecha($wecha_id)){
			$data["content"] = "微信已经注册，只需绑定即可";
			$this->ajaxReturn($data);
		}
        if (empty($company_email)) {
            $data["content"] = "email不能为空!";
            $this->ajaxReturn($data);
        }
		$data['company_name'] = $company_name;
		$data['company_phone'] = $company_phone;
		$data['company_pass'] = md5($company_pass);
		$data['company_tax'] = $company_tax;
		$data['tax_class'] = $tax_class;
		$data['company_username'] = $company_username;
		$data['wecha_id'] = $wecha_id;
		$data['company_date'] = date('Y-m-d H:i:s', time());
		$data['status'] = 1;
        $data['company_email'] = $company_email;
		$model = M('company');
		if($model->add($data)){
		$groupid = self::getgroupid($tax_class);
		$a = self::sendgroup($groupid,$wecha_id);
		$data['content'] = "注册成功。1秒后进入";
		$data['url']=U('Member/com',array('wecha_id'=>$_SESSION['wecha_id'],'token'=>$token));
		
		$this->ajaxReturn($data);
		}else{
		$data['content'] = "注册失败。1秒后进入";
		$this->ajaxReturn($data);
		}
	}
	//会员解除绑定
	public function unbangding(){
		$wecha_id = $_SESSION['wecha_id'];
		$this->assign('wecha_id',$wecha_id);
		$this->display();
	}
	
	//会员解除绑定操作
	public function ubd(){
		$username = $_POST['user_username'];
		$password = $_POST['user_pass'];
		
		
		if(empty($username)){
			$data["content"]="用户名不能为空！";
			$this->ajaxReturn($data);
		}
		if(empty($password)){
			$data["content"]="密码不能为空！";
			$this->ajaxReturn($data);
		}
		
		$data1['user_username'] = $username;
		$data1['user_pass'] = md5($password);
		
		$model = D('user');
		$user = $model->where($data1)->find();
		
		
		
		if($user){
			
			$da['status'] = 2;
			$where['user_id'] = $user['user_id'];
			if($model->where($where)->save($da)){
				
				unset($_SESSION['user_id']);
				unset($_SESSION['wecha_id']);
				$data['content'] = "退出成功。1秒后进入";
				$data['status'] = 1;
				$data['url']=U('first',array('wecha_id'=>$_SESSION['wecha_id'],'token'=>$token));
				$this->ajaxReturn($data);
			}else{
				$data["content"]="退出失败！";
				$this->ajaxReturn($data);
			}
		}else{
		
			$data2['company_username'] = $username;
			$data2['company_pass'] = md5($password);
			
			$model1 = M('company');
			$com = $model1->where($data2)->find();
			
			if($com){
				$da1['status'] = 2;
				$data3['company_id'] = $com['company_id'];
				$query=$model1->where($data3)->save($da1);
				
				if($query){
					$data['status'] = 1;
					$data['content'] = "退出成功";
					unset($_SESSION['user_id']);
					unset($_SESSION['wecha_id']);
					$data['url']=U('first',array('wecha_id'=>$wecha_id,'token'=>$token));
					$this->ajaxReturn($data);
				}else{
					$data["content"]="退出失败！";
					$this->ajaxReturn($data);
				}
			}else{
				$data["content"]="退出失败！";
				$this->ajaxReturn($data);
			}
		}
	}
	
	/* //绑定
	public function bangding($token='',$wecha_id=''){
		$this->assign('wecha_id',$wecha_id);
		$this->display();
	}
	
	
	//绑定操作
	public function bangdingdo(){
		$username = $_POST['user_name'];
		$password = $_POST['user_pass'];
		$wecha_id = $_POST['wecha_id'];
		if(empty($wecha_id)){
			$data["content"]="操作有错误！";
			$this->ajaxReturn($data);
		}
		if(empty($username)){
			$data["content"]="用户名不能为空！";
			$this->ajaxReturn($data);
		}
		if(empty($password)){
			$data["content"]="密码不能为空！";
			$this->ajaxReturn($data);
		}
		$data['user_username'] = $username;
		$data['user_pass'] = md5($password);
		$model = D('user');
		$user = $model->where($data)->find();
		if($user){
			$da['status'] = 1;
			if(!empty($wecha_id)){
				$da['wecha_id'] = $wecha_id;
			}	
			$where['user_id'] = $user['user_id'];
			if($model->where($where)->save($da)){
				$data['content'] = "绑定成功。1秒后进入";
				$_SESSION['user_id']= $user['user_id'];
				if(!empty($wecha_id)){
				$_SESSION['wecha_id'] = $wecha_id;
				}	
				$data['url']=U('Member/index',array('wecha_id'=>$wecha_id,'token'=>$token));
				$this->ajaxReturn($data);
			}
		}else{
			$data1['company_username'] = $username;
			$data1['company_pass'] = md5($password);
			
			$model1 = D('company');
			$com = $model1->where($data1)->select();
			if($com){
				$da1['status'] = 1;
				if(!empty($wecha_id)){
				$da1['wecha_id'] = $wecha_id;
				}	
				$data1['company_id'] = $com['company_id'];
				if($model1->where($data1)->save($da1)){
					$data['content'] = "绑定成功。1秒后进入";
					if(!empty($wecha_id)){
					$_SESSION['wecha_id'] = $wecha_id;
					}
					$_SESSION['user_id'] = $user['user_id'];
					$data['url']=U('Member/index',array('wecha_id'=>$wecha_id,'token'=>$token));
					$this->ajaxReturn($data);
				}
			}else{
				$data3["content"]="绑定失败！";
				$this->ajaxReturn($data3);
			}
		}
	
	
	
	}
	 */
	public function loginoutshow(){
		$this->display();
	
	}
	//loginout 
	public function loginout(){
		$model = M('user');
		$model1 = M('company');
		$where1['wecha_id'] = $_SESSION['wecha_id'];
		$where1['status'] = 1; 
		$user = $model->where($where1)->find();
		$data1['status'] = 3;
		if($user){
			$model->where($where1)->save($data1);
			$order = M('order');
			$orderdata['order_status'] = 5;
			$order->where($where1)->save($orderdata);
			unset($_SESSION['user_id']);
			unset($_SESSION['wecha_id']);
			
			$data['content'] = "注销成功";
			$data['status'] = 1;
			$data['url']=U('outsuccess');
			$this->ajaxReturn($data);
		}else{
			
			$where2['wecha_id'] = $_SESSION['wecha_id'];
			$where2['status'] = 1;
			$com = $model1->where($where2)->find();
			if($com){
				$model1->where($where2)->save($data1);
				$order = M('order');
				$orderdata['order_status'] = 5;
				$order->where($where1)->save($orderdata);
				unset($_SESSION['user_id']);
				unset($_SESSION['wecha_id']);
			
				$data['content'] ="注销成功";
				$data['status'] = 1;
				$data['url']=U('outsuccess');
				$this->ajaxReturn($data);
			}else{
				$data['content'] = "注销失败";
				$this->ajaxReturn($data);
			}	
		}
	}
	
	public function loginout1(){
		$model = M('user');
		$model1 = M('company');
		$where1['wecha_id'] = $_SESSION['wecha_id'];
		$where1['status'] = 1; 
		$user = $model->where($where1)->find();
		$data1['status'] = 2;
		if($user){
			$model->where($where1)->save($data1);
			
			unset($_SESSION['user_id']);
			unset($_SESSION['wecha_id']);
			
			$data['content'] = "退出成功";
			$data['status'] = 1;
			$data['url']=U('login');
			$this->ajaxReturn($data);
		}else{
			$where2['wecha_id'] = $_SESSION['wecha_id'];
			$where2['status'] = 1;
			$com = $model1->where($where2)->find();
			if($com){
				$model1->where($where2)->save($data1);
				
				unset($_SESSION['user_id']);
				unset($_SESSION['wecha_id']);
			
				$data['content'] ="退出成功";
				$data['status'] = 1;
				$data['url']=U('login');
				$this->ajaxReturn($data);
			}else{
				$data['content'] = "退出失败";
				$this->ajaxReturn($data);
			}	
		}
	}
	
	
	public function outsuccess(){
		$this->display();
	}
	public function login(){
		$op = $_GET['op'];
        if ($op == 'appointment') {
            $this->assign('has_new', false);
        } else {
            $this->assign('has_new', true);
        }
		$this->display();
	}

    public function RetrievePassword(){
        $this->display();
    }
	
	public function login1($msg=''){
		$this->assign('msg',$msg);
		$this->display();
	}
	
	public function logindo(){
		
		$username = $_POST['user_username'];
		$password = $_POST['user_pass'];
		
		if(empty($username)){
			$data["content"]="用户名不能为空！";
			$this->ajaxReturn($data);
		}
		if(empty($password)){
			$data["content"]="密码不能为空！";
			$this->ajaxReturn($data);
		}
		
		$data['user_username'] = $username;
		$data['user_pass'] = md5($password);
		$d['status'] = 1;
		$model = D('user');
		$user = $model->where($data)->find();
		if($user){
				$model->where($data)->save($d);
				$_SESSION['user_id'] = $user['user_id'];
				$_SESSION['wecha_id'] = $user['wecha_id'];
			
				$data1['status'] = 1;
				$data1['content'] = "登录成功";
				
				$data1['url']=U('Member/index',array('token'=>'','wecha_id'=>$user['wecha_id']));
				$this->ajaxReturn($data1);
			
		}else{
			$data1['company_username'] = $username;
			$data1['company_pass'] = md5($password);
			
			$model1 = D('company');
			$com = $model1->where($data1)->find();
			if($com){
				$model1->where($data1)->save($d);
				$_SESSION['user_id']= $com['company_id'];
				$_SESSION['wecha_id'] = $com['wecha_id'];
				$data2['url']=U('Member/index',array('token'=>'','wecha_id'=>$com['wecha_id']));
				$data2['content'] = "登录成功";
				$data2['status'] = 1;
				$this->ajaxReturn($data2);
			}else{
				if(self::checkUsername($username)){
					$data3["content"]="登录密码输入错误！";
					$this->ajaxReturn($data3);
				}else{
				$data3["content"]="用户名输入错误！";
				$this->ajaxReturn($data3);
				}
			}
	}
	}
	
	function checkUsername($name=''){
		$model = M("user");
		$model1 = M("company");
		if(!empty($name)){
			$where['user_username'] = $name;
			$where['status'] = array('in','1,2');
			$vo = $model->where($where)->find();
			if($vo){
				return true;
			}else{
				$where1['company_username'] = $phone;
				$where1['status'] = array('in','1,2');
				$vo1 = $model1->where($where1)->find();
				if($vo1){
					return true;
				}else{
					return false;
				}
			}
		}
	}
	
	function checkWecha($wecha_id=''){
		$model = M('user');
		$model1 = M('company');
		$data['status'] = array('in','1,2');
		$data['wecha_id'] = $wecha_id;
		$vo = $model->where($data)->find();
		if($vo){
			return true;
		}else{
			$vo1=$model1->where($data)->find();
			if($vo1){
				return true;
			}else{
				return false;
			}
		}
	}
	
	function checkwe($user_id,$wecha_id){
		$model = M('user');
		$model1 = M('company');
		$data['wecha_id'] = $wecha_id;
		$vo = $model->where($data)->find();
		$return = 1;
		if($vo){
			if($vo['user_id']==$user_id){
				
				$return =1;
			}else {
				$return =2;
			}
		}else{
			$vo1=$model1->where($data)->find();
			if($vo1){
				if($vo1['company_id']== $user_id){
					$return = 1;
				}else{
					$return =2;
				}
			}else{
				$return = 3;
			}
		}
		return $return;
	}
	
	
	//得到Accesstoken
	public function getAccesstoken(){
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=&secret=";
		$res = self::https_request($url);
		return json_decode($res,true);
	}
	
	public function sendgroup($id,$name=''){
		$access_token = self::getAccesstoken();
		
		$token = $access_token['access_token'];
		$url="https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token=".$token;
		$msg['openid'] = $name;
		$msg['to_groupid'] = $id;
		$res = self::curlPost($url,json_encode($msg));
		
		return json_decode($res,true);
		
	}
	
	public function curlPost($url,$data) {
		
        $curl = curl_init(); //启动一个curl会话
        curl_setopt($curl, CURLOPT_URL, $url); //要访问的地址 
         curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");  
         curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);  
         curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);  
         curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');  
         curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);  
         curl_setopt($curl, CURLOPT_AUTOREFERER, 1);  
         curl_setopt($curl, CURLOPT_POSTFIELDS, $data);  
         curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  
        $result = curl_exec($curl); //执行一个curl会话
        curl_close($curl); //关闭curl
        return $result;
    }
	
	function https_request($url)
	{
   $curl = curl_init();
   curl_setopt($curl, CURLOPT_URL, $url);
   curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
   curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   $data = curl_exec($curl);
   if (curl_errno($curl)) {return 'ERROR '.curl_error($curl);}
   curl_close($curl);
   return $data;
  }
	
  function getgroupid($name=''){
	$groups = array(
	"第二税务所"=>102,
	"第三税务所"=>103,
	"第四税务所"=>104,
	"第五税务所"=>105,
	"第六税务所"=>106,
	"第七税务所"=>107,
	"第八税务所"=>108,
	"第九税务所"=>109,
	"第十二税务所"=>110,
	"不清楚所属税务所"=>111,
	"个体"=>112
	);
	$re = $groups[$name];
	return $re;
  }

}

?>