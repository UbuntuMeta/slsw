<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class MemberController extends UserWxController {
	
    public function index($token='',$wecha_id=''){
		if($wecha_id){
			$where['wecha_id'] = $wecha_id;
			$where['status'] = array('in','1,2');
			$From = D("user");
			$user = $From->where($where)->find();
			$_SESSION['wecha_id'] = $wecha_id;
			if($user){
				if($user['status']==1){
				$_SESSION['user_id']=$user['user_id'];
				$_SESSION['user_name'] = $user['user_username'];
				redirect(U('Member/user',array('token'=>$token,'wecha_id'=>$wecha_id)));
				}else{
				redirect(U('Login/login1',array('msg'=>'你已经注册了，可以直接登录')));
				}
			}else {
				$From1 = D('company');
				$com = $From1->where($where)->find();
				if($com){
					$_SESSION['user_id']=$com['company_id'];
					$_SESSION['user_name'] = $com['company_username'];
					if($com['status'] == 1){
					if(empty($com['company_class'])){
						$this->assign('com',$com);
						redirect(U('Member/com',array('token'=>$token,'wecha_id'=>$wecha_id)));
					}else{
						$this->assign('com',$com);
						redirect(U('Member/company',array('token'=>$token,'wecha_id'=>$wecha_id)));
					}
					}else{
						redirect(U('Login/login1',array('msg'=>'你已经注册了，可以直接登录')));
					}
					
				}else{
					unset($_SESSION['user_id']);
				}
			}
			redirect(U('Login/first',array('token'=>$token,'wecha_id'=>$wecha_id)));
			
		}
		
    }
	
	public function user($token='',$wecha_id=''){
		if($wecha_id){
			$where['wecha_id'] = $wecha_id;
			$where['status'] = 1;
			$From = D("user");
			$user = $From->where($where)->find();
			if($user){
				$_SESSION['user_id']=$user['user_id'];
				
			}else{
				unset($_SESSION['user_id']);
			}
			$_SESSION['wecha_id']=$wecha_id;	
		}
		$url = C('HTTP_URL').U('Order/first',array('wecha_id'=>$wecha_id));
		$this->assign('pcurl',$url);
		$this->assign('user',$user);
		$this->display();
	}

    public function findBack()
    {

        $user_email = $_POST['user_email'];
        // 假如是一般个人
        $modelname = 'user';
        $From1 = D($modelname);
        $user_exist = false;
        if ($res = $From1->where(array('user_email' => $user_email))->find()) {
            $jres = self::ResetPwd($modelname, $res, $From1);
            $user_exist = true;
        }
        $modelname = 'company';
        $From2 = D($modelname);
        $cuser_exist = false;
        if ($res2 = $From2->where(array('company_email' => $user_email))->find()) {
            $jres2 = self::ResetPwd($modelname, $res2, $From2);
            $cuser_exist = true;
        }

        if((!$user_exist) && (!$cuser_exist)) {
            echo '该邮箱没有绑定用户!';
        } else {
            if(isset($jres2)) {
                echo $jres2;
            } elseif(isset($jres)) {
                echo $jres;
            }
        }

    }
    public function setPwd () {
        if ($_GET['user_id']) {
            $id = $_GET['user_id'] + 0;
            $From1 = D('user');
            if ($res = $From1->where(array('user_id' => $id))->find()) {
                $this->assign('user_type', 'user');
                $this->assign('id', $id);
            } else {
                echo '沒有账号存在!';
                die();
            }

        } else if ($_GET['company_id']) {
            $id = $_GET['company_id'] + 0;
            $From1 = D('company');
            if ($res = $From1->where(array('company_id' => $id))->find()) {
                $this->assign('user_type', 'company');
                $this->assign('id', $id);
            } else {
                echo '沒有账号存在!';
                die();
            }
        }
        $this->display();
    }


    public function ChangePwd() {
        if (isset($_POST['id'])&&isset($_POST['user_type'])&&isset($_POST['new_pwd'])) {
            if (in_array($_POST['user_type'], array('user', 'company'))) {
                $modelname = $_POST['user_type'];
            } else {
                echo 'error param';
                die();
            }
            $id = $_POST['id'] + 0;
            $From1 = D($modelname);
            if (strlen($_POST['new_pwd']) < 6) {
                echo '密码不能小于6位！';
                die();
            }
            $newPwd = md5($_POST['new_pwd']);
            if ($res = $From1->where(array($modelname . '_id' => $id))->find()) {
                $query = $From1->where(array($modelname . '_id' => $id))
                    ->save(array($modelname . '_pass' => $newPwd));
                if ($query) {
                    echo 'success';
                    die();
                } else {
                    echo "更新失败，和之前设置的密码一样";
                    die();
                }

            } else {
                echo '该账号不存在!';
                die();

            }
        } else {
            echo '参数有错误';
            die();
        }


    }

    public static function ResetPwd($modelname, $res,$From1) {
        // 随机密码生成
        $round_str = 'asdfghjklqwertyuiopzxcvbnm1234567890,.!;@';
        $new_pwd = substr(str_shuffle($round_str),0 , 7);
        $data[$modelname . '_pass'] = md5($new_pwd);
        $condition = array();
        $condition[$modelname .'_id'] = $res[$modelname . '_id'];
        if ($reult = $From1->where($condition)->save($data)) {

            // 更新密码到数据表
            // 发送邮件通知
            Vendor('PHPMailer.phpmailer');
            Vendor('PHPMailer.smtp');
            $mail = new \PHPMailer(); //实例化
            $mail->IsSMTP(); // 启用SMTP
            $mail->Host = "smtp.163.com"; //SMTP服务器 以163邮箱为例子
            $mail->Port = 25;  //邮件发送端口
            $mail->SMTPAuth   = true;  //启用SMTP认证

            $mail->CharSet  = "UTF-8"; //字符集
            $mail->Encoding = "base64"; //编码方式

            $mail->Username = "fightforphp@163.com";  //你的邮箱
            $mail->Password = "1234567qaz";  //你的密码
            $mail->Subject = "你好"; //邮件标题

            $mail->From = "fightforphp@163.com";  //发件人地址（也就是你的邮箱）
            $mail->FromName = "fightforphp";  //发件人姓名

            $address = $res[$modelname .'_email'];
            $mail->AddAddress($address, "亲");//添加收件人（地址，昵称）

            $mail->IsHTML(true); //支持html格式内容
            $mail->Body = '你好, <b>用户:' . $res[$modelname . '_username'] . '</b>!你已经申请找回密码! <br/>
                    你临时密码为:' . $new_pwd . '
                    <br/>
                    登录后,请重置密码!
                    ';

            //发送
            if(!$mail->Send()) {
                return json_encode("failed");
            } else {
                return "success";
            }

        } else {
            return json_encode('update failed');
        }
    }
	public function com($token='',$wecha_id=''){
		if($wecha_id){
			$where['wecha_id'] = $wecha_id;
			$where['status'] = 1;
			$From1 = D('company');
			$com = $From1->where($where)->find();
			if($com){
			$_SESSION['user_id']=$com['company_id'];
			}else{
				unset($_SESSION['user_id']);
			}
			$_SESSION['wecha_id']=$wecha_id;
		}
		$url = C('HTTP_URL').U('Order/first',array('wecha_id'=>$wecha_id));
		$this->assign('pcurl',$url);
		$this->assign('com',$com);
		$this->display();
	}
	
	public function company($wecha_id='',$token=''){
		if($wecha_id){
			$where['wecha_id'] = $wecha_id;
			$where['status'] = 1;
			$From1 = D('company');
			$com = $From1->where($where)->find();
			if($com){
			$_SESSION['user_id']=$com['company_id'];
			}else{
				unset($_SESSION['user_id']);
			}
			$_SESSION['wecha_id']=$wecha_id;
		}
		$url = C('HTTP_URL').U('Order/first',array('wecha_id'=>$wecha_id));
		$this->assign('pcurl',$url);
		$this->assign('company',$com);
		$this->display();
	}
	public function userupdate(){
			
			$where['user_id'] = $_SESSION['user_id'];
			
			$where['status'] = 1;
			$From = D("user");
			$user = $From->field('wecha_id,user_id,user_name,user_phone,user_username,user_sfz,tax_class')->where($where)->find();
			if($user){
				
				$_SESSION['user_id']=$user['user_id'];
			
			}else{
				unset($_SESSION['user_id']);
			}
			
			
		
		$this->assign('user',$user);
		$this->display();
	
	}
	public function updateuserdo(){
		$user_name = $_POST['user_name'];
		$user_phone = $_POST['user_phone'];
		$user_sfz = $_POST['user_sfz'];
		$user_tax = $_POST['taxclass'];
		if(empty($user_phone)){
			$data["content"] = "手机号不能为空";
			$this->ajaxReturn($data);
		}
		if(empty($user_sfz)){
			$data["content"] = "身份证不能为空";
			$this->ajaxReturn($data);
		}
		
		$data['user_name'] = $user_name;
		$data['user_phone'] = $user_phone;
		$data['user_sfz'] = $user_sfz;
		$data['tax_class'] = $user_tax;
		
			
		$model = M('user');
		$where['user_id'] = $_SESSION['user_id'];
		$where['status'] = 1;
		$query = $model->where($where)->save($data);
		if($query){
		/* $groupid = self::getgroupid("个体");
		$a = self::sendgroup($groupid,$_SESSION['wecha_id']); */
		$data['content'] = '修改成功';
		$data['status'] = 1;
		
		$data['url']=U('Member/user',array('token'=>$token,'wecha_id'=>$_SESSION['wecha_id']));
		$this->ajaxReturn($data);
		}else{
		$data['content'] = "修改失败";
		$this->ajaxReturn($data);
		}
	}
	
	
	public function updatecompany(){
			if($_SESSION['user_id']){
		
			$where['company_id'] = $_SESSION['user_id'];
			}else if($_SESSION['wecha_id']){
			$where['wecha_id'] = $_SESSION['wecha_id'];
			}
			$where['status'] = 1;
			$From1 = D('company');
			$com = $From1->where($where)->find();
			$_SESSION['user_id'] = $com['company_id'];
			
		$this->assign('company',$com);
		$this->display();
	
	}
	
	
	public function updatecompanydo(){
		
		$company_name = $_POST['company_name'];
		$company_phone = $_POST['company_phone'];
	
		$company_tax = $_POST['company_tax'];
		$tax_class = $_POST['tax_class'];
		$company_class = $_POST['company_class'];
		
		if(empty($company_name)){
			$data["content"]="公司名不能为空！";
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
		
		
		$data['company_name'] = $company_name;
		$data['company_phone'] = $company_phone;
		
		$data['company_tax'] = $company_tax;
		$data['tax_class'] = $tax_class;
		$data['company_class'] = $company_class;
		$where['wecha_id'] = $_SESSION['wecha_id'];
		$where['status'] = 1;
		$model = M('company');
		$query = $model->where($where)->save($data);
		
		if($query){
		/* $groupid = self::getgroupid($tax_class);
		$a = self::sendgroup($groupid,$_SESSION['wecha_id']); */
		$data['status'] = 1;
		$data['content'] = "修改成功";
		$data['url']=U('Member/company',array('wecha_id'=>$_SESSION['wecha_id'],'token'=>$token));
		$this->ajaxReturn($data);
		
		}else{
		$data['content'] = "修改失败";
		$this->ajaxReturn($data);
		
		}
	}
	public function updatecom(){
		if($_SESSION['user_id']){
		
			$where['company_id'] = $_SESSION['user_id'];
			}else if($_SESSION['wecha_id']){
			$where['wecha_id'] = $_SESSION['wecha_id'];
			}
			$where['status'] = 1;
			$From1 = D('company');
			$com = $From1->where($where)->find();
			$_SESSION['user_id'] = $com['company_id'];
		$this->assign('company',$com);
		$this->display();
	
	
	
	}
	public function updatecomdo(){
		$company_name = $_POST['company_name'];
		$company_phone = $_POST['company_phone'];
	
		$company_tax = $_POST['company_tax'];
		$tax_class = $_POST['tax_class'];
	
		
		if(empty($company_name)){
			$data["content"]="公司名不能为空！";
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
		$data['company_name'] = $company_name;
		$data['company_phone'] = $company_phone;
		
		$data['company_tax'] = $company_tax;
		$data['tax_class'] = $tax_class;
		$where['status'] = 1;
		$where['wecha_id'] = $_SESSION['wecha_id'];
		$model = M('company');
		if($model->where($where)->save($data)){
		/* $groupid = self::getgroupid($tax_class);
		$a = self::sendgroup($groupid,$_SESSION['wecha_id']); */
		$data['status'] = 1;
		$data['content'] = "修改成功";
		$data['url']=U('Member/com',array('wecha_id'=>$_SESSION['wecha_id'],'token'=>$token));
		$this->ajaxReturn($data);
		}else{
		$data['content'] = "修改失败u";
		$this->ajaxReturn($data);
		}
	}
	
	//预约分类
	//1为已预约，2为已处理，3为预约未到 4已取消 5 删除
	public function orderlist(){
		$class_id = $_GET['class_id'];
		$model = M('order');
		$data['wecha_id'] =  $_SESSION['wecha_id'];
		$url1=U('Order/first',array('token'=>$token,'wecha_id'=>$_SESSION['wecha_id']));
		
		$data['people_class'] = $class_id;
		$data['order_status'] = array('neq',5);
		$volist = $model->where($data)->order('order_date desc')->select();
		
		for($i=0;$i<count($volist);$i++){
			if($volist[$i]['order_status']==1){
				$volist[$i]['url'] = "<a href='".C('HTTP_URL').U('Member/quxiao1',array('order_id'=>$volist[$i]['order_id']))."'>取消</a>";
				$volist[$i]['status_name'] = "已预约";
			}
			else if($volist[$i]['order_status']==2){
			
				$volist[$i]['status_name'] = "已处理";
			}else if($volist[$i]['order_status']==3){
			
				$volist[$i]['status_name'] = "未到";
			}else if($volist[$i]['order_status']==4){
			
				$volist[$i]['status_name'] = "已取消";
			}
			$model1 = M('business');
			$data1['business_id'] = $volist[$i]['order_business'];
			$data1['business_flas'] = 1;
			$business_name = $model1->where($data1)->find();
	
			$volist[$i]['business_name'] = $business_name['business_name'];
		}
		
	
		$this->assign('volist',$volist);
		$url=U('Member/index',array('wecha_id'=>$_SESSION['wecha_id'],'token'=>$token));
		$this->assign('purl',$url);
		$this->display();
	}
	public function quxiao1($order_id=''){
		$model = M('order');
		$where['order_status'] = 1;
		$where['order_id'] = $order_id;
		$order = $model->where($where)->find();
		$this->assign('vo',$order);
		$this->display();
	}
	public function quxiao($order_id=''){
		$model = M('order');
		$data['order_status'] = 4;
		$where['order_id'] = $order_id;
		$order = $model->where($where)->find();
		$now = date("Y-m-d");
		if(strtotime($now) - strtotime($order['order_date']) >=0){
			$data['content'] = "预约必须提前一天取消";
			$this->ajaxReturn($data);
		}
		$query = $model->where($where)->save($data);
		if($query){
		$orderset = M('orderset');
		$da['so_id'] = $order['so_id'];
		$orderset->where($da)->setDec('so_orderpeople');
		$data['status'] = 1;
		$data['content'] = "取消成功";
		$data['url']=U('Member/orderlist',array('class_id'=>$order['people_class']));
		$this->ajaxReturn($data);
		}else{
		$data['content'] = "预约取消失败";
			$this->ajaxReturn($data);
		}
	}
	
	//得到Accesstoken
	public function getAccesstoken(){
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=&secret=";
		$res = self::https_request($url);
		return json_decode($res,true);
	}
	
	public function sendgroup($id,$name=''){
		$access_token = self::getAccesstoken();
		var_dump($access_token);
		$token = $access_token['access_token'];
		$url="https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token=".$token;
		$msg['openid'] = $name;
		$msg['to_groupid'] = $id;
		$res = self::curlPost($url,json_encode($msg));
		var_dump($res);
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