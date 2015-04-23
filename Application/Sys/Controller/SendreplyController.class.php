<?php
namespace Sys\Controller;
use Think\Controller;

class SendreplyController extends SysadminController {
   public function insert(){
		$content = $_POST['content'];
		$tax = $_POST['tax'];
		$companyclass = $_POST['companyclass'];
		$peopleclass = $_POST['peopleclass'];
		
		if($peopleclass == 1){
			if(count($tax)>0){
				if(empty($companyclass)){
					for($i=0;$i<count($tax);$i++){
						$w = self::sendBygroup($tax[$i],$content);
					}
					$this->success('发送成功');
				}else{
					if($companyclass == 3){
						$companyname = '小型微利企业';
						$where['company_class'] = $companyname;
					}else if($companyclass == 4){
						$companyname = '非小型微利企业';
						$where['company_class'] = $companyname;
					}
					for($i=0;$i<count($tax);$i++){
						$taxclass = self::getgroupname($tax[$i]);
						$model = M('company');
						$where['tax_class'] = $taxclass;
						
						$where['status'] = 1;
						
						$volist = $model->where($where)->select();
						if(count($volist)<1){
							$this->error('发送用户为0');
						}
						
						
						for($j=0;$j<count($volist);$j++){
							array_push($arr,$volist[$j]['wecha_id']);
						}
						var_dump($arr);
						 $w=self::sendmsg($arr,$content); 
						
					}
					$this->success('发送成功');
				}
			}else{
				if($companyclass==3){
					$companyname = '小型微利企业';
					$where['company_class'] = $companyname;
				}else if($companyclass==4){
					$companyname = '非小型微利企业';
					$where['company_class'] = $companyname;
				}
				$model = M('company');
				$where['status'] = 1;
				$volist = $model->field('wecha_id')->where($where)->select();
				$arr = array();
				for($j=0;$j<count($volist);$j++){
					array_push($arr,$volist[$j]['wecha_id']);
				}
				
				$w = self::sendmsg($arr,$content); 
				$this->success('发送成功');
			}
		}else if($peopleclass == 2){
				if(count($tax)>0){
					for($i=0;$i<count($tax);$i++){
						 $w = self::sendBygroup($tax[$i],$content);
						
					}
					$this->success('发送成功');
					
				}
			/* self::sendBygroup('112',$content); */
				$model = M('user');
				
				$volist = $model->field('wecha_id')->where($where)->select();
				$arr = array();
				for($j=0;$j<count($volist);$j++){
					array_push($arr,$volist[$j]['wecha_id']);
				}
				$this->success('发送成功');
		}else if($peopleclass == 3){
			$users = self::getAllUser();
			 $w = self::sendmsg($users,$content);
			 $this->success('发送成功');
		}else if($peopleclass == 4){
			$com = M('company');
			$comdata['company_class'] = '个体工商户';
			$comdata['status'] = 1;
			$comlist = $com->where($comdata)->select();
			for($i=0;$i<count($comlist);$i++){
				$comarr[] = $comlist[$i]['wecha_id']; 
			}
			$w = self::sendmsg($comarr,$content); 
			$this->success('发送成功');
		}else{
			if($companyclass<2){
			if(count($tax)<1){
			$this->error('发送用户为0');
			}
			
			for($i=0;$i<count($tax);$i++){
			$w = self::sendBygroup($tax[$i],$content); 
			}
			/* $w=self::sendBygroup($tax,$content); */
			$this->success('发送成功');	
				
			}else{
				if($companyclass==3){
					$companyname = '小型微利企业';
					$where['company_class'] = $companyname;
				}else if($companyclass==4){
					$companyname = '非小型微利企业';
					$where['company_class'] = $companyname;
				}
				$model = M('company');
				$where['status'] = 1;
				$volist = $model->field('wecha_id')->where($where)->select();
				$arr = array();
				for($j=0;$j<count($volist);$j++){
					array_push($arr,$volist[$j]['wecha_id']);
				}
			 $w = self::sendmsg($arr,$content); 
			$this->success('发送成功');
			}
		}
   }
	  
	
   public function index() {
		$db=D("Article");
		//$where['articletype']='news';
		$count=$db->where($where)->count();
		$Page = new \Think\Page($count, $size=10); //count总数 $size每页显示数
		$limit = $Page->firstRow . ',' . $Page->listRows;
		$list=$db->where($where)->limit($Page->firstRow. ',' . $Page->listRows)->order("id desc")->select();
		$this->pager_bar = $Page->show(); //显示分页导航
		$this->assign('list',$list);
		$this->display();
	}


	
	
	
	public function del($id=''){
        empty($id) && $this->error('参数错误！');

        $Model = D('Article');
        //删除属性数据
        $res = $Model->delete($id);
        if(!$res){
            $this->error("删除失败");
        }else{
            //记录行为
            $this->success('删除成功', U('index'));
        }
    }
	
	//得到Accesstoken
	public function getAccesstoken(){
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=&secret=";
		$res = self::https_request($url);
		return json_decode($res,true);
	}
	
	
	
	public function curlPost($url,$data) {
		$header = array(
            'Accept:*/*',
            'Accept-Charset:GBK,utf-8;q=0.7,*;q=0.3',
            'Accept-Encoding:gzip,deflate,sdch',
            'Accept-Language:zh-CN,zh;q=0.8',
            'Connection:keep-alive',
			 
            'X-Requested-With:XMLHttpRequest'
        );
        $curl = curl_init(); //启动一个curl会话
        curl_setopt($curl, CURLOPT_URL, $url); //要访问的地址 
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
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
  
  
  	
  function getgroupname($id=''){
	$groups = array(
	'102'=>"第二税务所",
	'103'=>"第三税务所",
	'104'=>"第四税务所",
	'105'=>"第五税务所",
	'106'=>"第六税务所",
	'107'=>"第七税务所",
	'108'=>"第八税务所",
	'109'=>"第九税务所",
	'110'=>"第十二税务所",
	'111'=>"不清楚所属税务所"
	
	);
	$re = $groups[$id];
	return $re;
  }
  
  
  
	function sendmsg($arr,$content){
		$access_token = self::getAccesstoken();
		$token = $access_token['access_token'];
		$url="https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=".$token;
		$msg =array('touser'=>$arr);
		$msg['msgtype'] ='text';
		$msg['text'] = array('content'=>$content);
		
		$res = self::curlPost($url,self::decodeUnicode(json_encode($msg)));
		return json_decode($res,true);
  }
  
  function sendBygroup($groupid,$content){
		$access_token = self::getAccesstoken();
	
		$token = $access_token['access_token'];
		$url="https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=".$token;
		$msg = array('filter'=>array('group_id'=>$groupid,'is_to_all'=>false));
		
		$msg['msgtype'] ='text';
		$msg['text'] = array('content'=>$content);
		
		$res = self::curlPost($url,self::decodeUnicode(json_encode($msg)));
		return json_decode($res,true);
  }
  function decodeUnicode($str)
	{
    return preg_replace_callback('/\\\\u([0-9a-f]{4})/i',
        create_function(
            '$matches',
            'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");'
        ),
        $str);
	}
  
  function getAllUser(){
	$access_token = self::getAccesstoken();

	$token = $access_token['access_token'];
	$url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$token;
	$w = self::https_request($url);
	
	$arr = json_decode($w,true);
	return $arr['data']['openid'];
  }
  
  function getgroup(){
	$access_token = self::getAccesstoken();
	
	$token = $access_token['access_token'];
  }
 

  
  
 
}



?>


