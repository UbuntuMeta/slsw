<?php
namespace Home\Controller;
use Think\Controller;
class WeixinController extends Controller {
	//file_put_contents(dirname(__FILE__) . "/request.txt", $postStr,FILE_APPEND);
    private $token;
    private $fun;
    private $data = array();
	
	protected function _initialize(){
		
		if($_GET['token']){
			$this->token =$_GET['token'];
		}
		if($_GET['echostr']){
			if(self::checkSignature()){
				echo $_GET['echostr'];
				exit;
			}
		}
		C("URL_MODEL","0");
		
	}
	
	function checkSignature(){
        $args = array("signature", "timestamp", "nonce");
        foreach ($args as $arg)
            if (!isset($_GET[$arg]))
                return false;

        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
		
		$tmpArr = array($this->token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
	
    public function index()
    {
		$weixin = new \Think\Wechat($this->token,false);
		$this->data=$weixin->request();
		$this->reply($weixin);
		exit;
	}
	
	
	/*
	根据用户事件回复用户
	*/
	public function reply($wx)
    {
		//unset($_SESSION);exit;
		self::check_weixin_user($this->data['FromUserName']);
		//记录用户
		//cookie('user_token_id',$this->data['FromUserName']);
		//file_put_contents(dirname(__FILE__) . "/request.txt", $_COOKIE['user_token_id'],FILE_APPEND);
		if(strtolower($this->data['MsgType'])=='event'){
			if ('subscribe' == strtolower($this->data['Event'])) {//关注
				$this->requestdata ( 'follownum' );
				$arg= $this->subscribe();
			}elseif ('unsubscribe' == strtolower($this->data['Event'])) {//删除关注
			    $this->requestdata ( 'unfollownum' );
				$arg= $this->unsubscribe();
			}elseif('click' == strtolower($this->data['Event'])){//菜单事件
				$this->requestdata ( 'textnum' );
				$arg=$this->search_event(strtolower($this->data['EventKey']));
			}
		}else{
			
			//用户手动输入关键字
			if($this->data['Content']=="999"){
				$purl=C('HTTP_URL').U('Login/index',array('token'=>$this->token,'wecha_id'=>$this->data['FromUserName']));
				$arg="亲，我不在线哦;.您还未绑定微信号哦！";
				//$arg= $this->subscribe();
			}elseif(strtolower($this->data['Content'])=="wj"){
				$purl=C('HTTP_URL').U('Wenjuan/index',array('token'=>$this->token,'wecha_id'=>$this->data['FromUserName']));
				$arg="亲，开启问卷调查<a href='".$purl."'> 马上进入</a>";
				//$arg= $this->subscribe();
			}else{
				$arg=$this->search_key($this->data['Content']);
			}
			$this->requestdata ('other');
			//self::requestdata('输入',$this->data['Content']);
		}
		
		//集中处理转化数据并推送到客户端。
		if (!is_array($arg)){
			$ret = $wx->replyText($arg);
        }elseif(array_key_exists("murl",$arg)){
            $ret = $wx->replyMusic($arg);
        }else{
			$ret = $wx->replyNews($arg);
        }
		//self::requestdata($type='',$typecount='',$methods='');
        echo $ret;
    }
	
	function shouye(){
		//$where['token']=$this->token;
		$where['method']='myhome';
		$vo=D("Weixin_key")->where($where)->find();
		if($vo){
			$return['title']=$vo['title'];
			$return['description']=html_entity_decode($vo['description']);
			$return['pic']=$vo['picurl'];
			$return['url']=C('HTTP_URL').U('Index/index',array('token'=>$this->token,'wecha_id'=>$this->data['FromUserName']));
		}else{
			$return="亲，我不在线哦，详细请咨询400-6887-666";
		}
		return $return;
	}
	//新关键字搜索
	function search_key($key){
		if($key>=100 && $key<=103){
			return self::product($key);exit;
		}
		$where['wxkey']=array('like',"%$key%");//$key;
		$From_key=D("Weixin_key");
		$list=$From_key->field("tableid,table")->where($where)->find();
		if(!is_array($list)){
			$return=self::Get_Nothing("vacant");
		}else{
			$return=self::$list["table"]($list["tableid"]);
		}
		return $return;
	}
	
	//关键字收索价格区域
	function product($typeid){
		$url=C('HTTP_API')."/kxapi/product_api.class.php?fun_name=ProductList&tid=".$typeid;
		$list = json_decode(file_get_contents($url),TRUE);
		if($list){
			if(count($list)==1){
				$return[0]['title']=$list[0]['title'];
				$return[0]['description']=html_entity_decode($list[0]['intro']);
				$array1=explode("|",$list[0]['pic']);
				if($array1[0]){
					if((strpos($array1[0],"http://")===false) || (strpos($array1[0],"https://")===false)){
						$return[0]['pic']=C('HTTP_URL').$array1[0];
					}else{
						$return[0]['pic']=$array1[0];
					}
				}
				$return[0]['url']=C('HTTP_URL').U("Product/show",array('id'=>$list[0]['id'],'token'=>$this->token,'wecha_id'=>$this->data['FromUserName']));
			}else{
				foreach($list as $key => $val){
					$return[$key]['title']=$val['title'];
					$return[$key]['description']=html_entity_decode($val['intro']);
					$array1=explode("|",$val['pic']);
					if($array1[0]){
						if((strpos($array1[0],"http://")===false) || (strpos($array1[0],"https://")===false)){
							$return[$key]['pic']="http://com/".$array1[0];
						}else{
							$return[$key]['pic']="http://com/".$array1[0];
						}
					}
					$return[$key]['url']=C('HTTP_URL').U("Product/show",array('id'=>$val['id'],'token'=>$this->token,'wecha_id'=>$this->data['FromUserName']));
				}
			}
		}else{
			$return=self::Get_Nothing("vacant");
		}

//		$db=D("Product");
//		$where['typeid']=$typeid;
//		$where['is_show']='1';
//		$list=$db->field("id,title,intro,pic,url")->where($where)->limit(8)->order("id desc")->select();
//		if($list){
//			if(count($list)==1){
//				$return[0]['title']=$list[0]['title'];
//				$return[0]['description']=html_entity_decode($list[0]['intro']);
//				if($list[0]['pic']){
//					if((strpos($list[0]['pic'],"http://")===false) || (strpos($list[0]['pic'],"https://")===false)){
//						$return[0]['pic']=C('HTTP_URL').$list[0]['pic'];
//					}else{
//						$return[0]['pic']=$list[0]['pic'];
//					}
//				}
//				if($list[0]['url']){
//					$return[0]['url']=$list[0]['url'];
//				}else{
//					$return[0]['url']=C('HTTP_URL').U("Product/show",array('id'=>$list[0]['id'],'token'=>$this->token,'wecha_id'=>$this->data['FromUserName']));
//				}
//			}else{
//				foreach($list as $key => $val){
//					$return[$key]['title']=$val['title'];
//					$return[$key]['description']=html_entity_decode($val['intro']);
//					if($val['pic']){
//						if((strpos($val['pic'],"http://")===false) || (strpos($val['pic'],"https://")===false)){
//							$return[$key]['pic']=C('HTTP_URL').$val['pic'];
//						}else{
//							$return[$key]['pic']=$val['pic'];
//						}
//					}
//					if($val['url']){
//						$return[$key]['url']=$val['url'];
//					}else{
//						$return[$key]['url']=C('HTTP_URL').U("Product/show",array('id'=>$val['id'],'token'=>$this->token,'wecha_id'=>$this->data['FromUserName']));
//					}
//				}
//			}
//		}else{
//			$return=self::Get_Nothing("vacant");
//		}
		return $return;
	}
		
	//菜单事件
	function search_event($event){
		$str=explode("_",$event);
		if(!is_array($str)){
			$return=self::Get_Nothing("vacant");
		}else{
			if($str[2]){ //2表示具体的方法
				$funs=$str[0]."_".$str[2];
			}else{
				$funs=$str[0];
			}
			$return=$this->$funs("$str[1]");
		}
		$vo=D("Weixin_menu")->field("title")->find($str[1]);
		self::requestdata_('点击菜单',$vo['title']);
		return $return;
	}
	
	//微信用户关注。。。
	function subscribe(){
//		$date['wecha_id']=$this->data['FromUserName'];
//		$From=D("Rm_vip_info_wx");
//		$purl=C('HTTP_URL').U('Login/index',array('token'=>$this->token,'wecha_id'=>$this->data['FromUserName']));
//		$rid=$From->field("wecha_id,id,uid")->where($date)->find();
//		if($rid){
//			$where['method']='subscribe';
//			$vov=D("Weixin_key")->where($where)->find();
//			if($vo){
//				if($vo['is_txt']){
//					$return=$vo['description'];
//				}else{
//					$return['title']=$vo['title'];
//					$return['description']=$vo['description'];
//					$return['pic']=$vo['picurl'];
//					$return['url']=C('HTTP_URL').U('Index/index',array('token'=>$this->token,'wecha_id'=>$this->data['FromUserName']));
//				}
//			}else{
//				$return="亲，我不在线哦;详细请咨询400-6887-666";
//			}
//		}else{
			$where['method']='subscribe';
			$vo=D("Weixin_key")->where($where)->find();
			if($vo){
				if($vo['is_txt']){
					$return=html_entity_decode($vo['description']);
				}else{
					$return['title']=$vo['title'];
					$return['description']=html_entity_decode($vo['description']);
					if($vo['picurl']){
						if((strpos($vo['picurl'],"http://")===false) || (strpos($vo['picurl'],"https://")===false)){
							$return['pic']=C('HTTP_URL').$vo['picurl'];
						}else{
							$return['pic']=$vo['picurl'];
						}
					}
					$return['url']=C('HTTP_URL').U("Index/index",array('token'=>$this->token,'wecha_id'=>$this->data['FromUserName']));
				}
			}else{
				$return=self::Get_Nothing("vacant");
			}
			return $return;
		}
//		return $return;
//	}
	
	function unsubscribe(){
		$where['method']='subscribe';
		$vo=D("Weixin_key")->where($where)->find();
		if($vo){
			if($vo['is_txt']){
				$return=html_entity_decode($vo['description']);
			}else{
				$return['title']=$vo['title'];
				$return['description']=html_entity_decode($vo['description']);
				if($vo['picurl']){
					if((strpos($vo['picurl'],"http://")===false) || (strpos($vo['picurl'],"https://")===false)){
						$return['pic']=C('HTTP_URL').$vo['picurl'];
					}else{
						$return['pic']=$vo['picurl'];
					}
				}
				$return['url']=C('HTTP_URL').U("Index/index",array('token'=>$this->token,'wecha_id'=>$this->data['FromUserName']));
			}
		}else{
			$return=self::Get_Nothing("vacant");
		}
		return $return;
	}
	
	function article($str=''){
		$db=D("Article");
		$where['menu_id']=$str;
		$where['is_show']='1';
		$list=$db->field("id,title,intro,pic")->where($where)->limit(10)->order("id desc")->select();
		if($list){
			if(count($list)==1){
				$return[0]['title']=$list[0]['title'];
				$return[0]['description']=html_entity_decode($list[0]['intro']);
				if($list[0]['pic']){
					if((strpos($list[0]['pic'],"http://")===false) || (strpos($list[0]['pic'],"https://")===false)){
						$return[0]['pic']=C('HTTP_URL').$list[0]['pic'];
					}else{
						$return[0]['pic']=$list[0]['pic'];
					}
				}
				$return[0]['url']=C('HTTP_URL').U("Article/show",array('id'=>$list[0]['id'],'token'=>$this->token,'wecha_id'=>$this->data['FromUserName']));
			}else{
				foreach($list as $key => $val){
					$return[$key]['title']=$val['title'];
					$return[$key]['description']=html_entity_decode($val['intro']);
					if($val['pic']){
						if((strpos($val['pic'],"http://")===false) || (strpos($val['pic'],"https://")===false)){
							$return[$key]['pic']=C('HTTP_URL').$val['pic'];
						}else{
							$return[$key]['pic']=$val['pic'];
						}
					}
					$return[$key]['url']=C('HTTP_URL').U("Article/show",array('id'=>$val['id'],'token'=>$this->token,'wecha_id'=>$this->data['FromUserName']));
				}
			}
		}else{
			$return=self::Get_Nothing("vacant");
		}
		return $return;
	}
	
	//无人接听功能查询
	function Get_Nothing($vacant=''){
		$whereNothing["wxkey"]=$vacant;		
		$From_Nothing=D("Weixin_key");
		$vos=$From_Nothing->where($whereNothing)->find();
		if(!empty($vos)){
			if($vos['is_txt']){
				$return=$vos['description'];
			}else{
				$return['title']=$vos['title'];
				$return['description']=html_entity_decode($vos['description']);
				if($vos['picurl']){
					if((strpos($vos['picurl'],"http://")===false) || (strpos($vos['picurl'],"https://")===false)){
						$return['pic']=C('HTTP_URL').$vos['picurl'];
					}else{
						$return['pic']=$vos['picurl'];
					}
				}
				$return['url']=C('HTTP_URL').U("Index/index",array('token'=>$this->token,'wecha_id'=>$this->data['FromUserName']));
			}
		}else{
			$return="亲，我不在线哦，详细请咨询400-6887-666";
		}
		return $return;
	}
	
	function lbs($str=''){
		$where['method']='lbs';
		//$where['menu_id']=$str;
		$vo=D("Weixin_menu")->field('description')->where($where)->find();
		if($vo){
			//if($vo['is_txt']){
				$return=html_entity_decode($vo['description']);
			//}else{
				//$return['title']=$vo['title'];
				//$return['description']=$vo['description'];
				//if($vo['picurl']){
					//if((strpos($vo['picurl'],"http://")===false) || (strpos($vo['picurl'],"https://")===false)){
					//	$return['pic']=C('HTTP_URL').$vo['picurl'];
					//}else{
						//$return['pic']=$vo['picurl'];
					//}
				//}
				//$return['url']=C('HTTP_URL').U("Index/index",array('token'=>$this->token,'wecha_id'=>$this->data['FromUserName']));
			//}
		}else{
			$return=self::Get_Nothing("vacant");
		}
		return $return;
	}
	
	//地图定位
	function shop_list(){
		$lat=$this->data['Location_X'];
		$lng=$this->data['Location_Y'];
		
		$range = 180 / pi() * 3 / 6372.797;     //里面的 3 就代表搜索 3km 之内，单位km  
		$lngR = $range / cos($lat * pi() / 180);
		$maxLat = $lat + $range;//最大纬度
		$minLat = $lat - $range;//最小纬度  
		$maxLng = $lng + $lngR;//最大经度  
		$minLng = $lng - $lngR;//最小经度  
		
		
		$map['lng']  = array('between',array($minLng,$maxLng)); //经度值
		$map['lat']  = array('between',array($minLat,$maxLat)); //纬度值
		
		$From=D("Shop");
		$list=$From->field("name,tel,address,picurl,id,lat,lng")->where($map)->select();
		if($list){
			foreach($list as $key => $val){
				//计算距离。
				$list[$key]['distance']=Get_Distance($lat,$lng,$val['lat'],$val['lng']);
			}
			
			foreach($list as $key => $val){
				//计算距离。
				$list=list_sort_by($list,"distance","asc");
			}
		}
		if(!empty($list)){
			if(count($list)==1){
				$return[0]['title']=$list[0]['name']." - 大约".$list[0]['distance']."Km";
				$return[0]['description']="地址:".$list[0]['address'];
				if($list[0]['picurl']){
					if((strpos($list[0]['picurl'],"http://")===false) || (strpos($list[0]['picurl'],"https://")===false)){
						$return[0]['pic']=C('HTTP_URL').$list[0]['picurl'];
					}else{
						$return[0]['pic']=$list[0]['picurl'];
					}
				}else{
					$return[0]['pic']=C('HTTP_URL')."/Public/images/no_shop_logo.jpg";
				}
				$return[0]['url']=C('HTTP_URL').U("Lbs/show",array('id'=>$list[0]['id'],'token'=>$this->token,'wecha_id'=>$this->data['FromUserName'],'pots'=>$lat."_".$lng));
			}else{
				foreach($list as $key => $val){
					if($key<10){
						$return[$key]['title']=$val['name']." - 大约".$val['distance']."Km";
						$return[$key]['description']="地址:".$val['address'];
						if($val['picurl']){
							if((strpos($val['picurl'],"http://")===false) || (strpos($val['picurl'],"https://")===false)){
								$return[$key]['pic']=C('HTTP_URL').$val['picurl'];
							}else{
								$return[$key]['pic']=$val['picurl'];
							}
						}else{
							$return[$key]['pic']=C('HTTP_URL')."/Public/images/no_shop_logo.jpg";
						}
						$return[$key]['url']=C('HTTP_URL').U("Lbs/show",array('id'=>$val['id'],'token'=>$this->token,'wecha_id'=>$this->data['FromUserName'],'pots'=>$lat."_".$lng));
					}
				}
			}
		}else{
			$return=self::Get_Nothing("vacant");
		}
		return $return;
	}
	function userregister(){
		$data['wecha_id'] = $this->data['FromUserName'];
		$From = D('user');
		$this->assign('wecha_id',$data['wecha_id']);
		$this->display('Login/attestation');
	}
	
	function member_companyregister(){
		$where['wecha_id'] = $this->data['FromUserName'];
		$where['status'] = array('in','1,2');
		$From = D('user');
		$user = $From->where($where)->find();
		if($user){
			if($user['status']==1){
			$return="您好，感谢您关注四川省双流县地方税务局。<a href='".C('HTTP_URL').U('Member/index',array('token'=>$this->token,'wecha_id'=>$this->data['FromUserName']))."'>点击进入个人中心</a>";
			}else{
			$return="您好，感谢您关注四川省双流县地方税务局。<a href='".C('HTTP_URL').U('Login/login')."'>点击这里进行登录</a>";
			/* $return = C('HTTP_URL').U('Member/memberbangding',array('token'=>$this->token,'wecha_id'=>$this->data['FromUserName']));
			 */}
		}else{
		$From1 = D('company');
		$com = $From1->where($where)->find();
		if($com){
			if($com['status'] ==1){
					$return="您好，感谢您关注四川省双流县地方税务局。<a href='".C('HTTP_URL').U('Member/index',array('token'=>$this->token,'wecha_id'=>$this->data['FromUserName']))."'>点击进入个人中心</a>";
					}else{
					$return="您好，感谢您关注四川省双流县地方税务局。<a href='".C('HTTP_URL').U('Login/login')."'>点击这里进行登录</a>";
					}
					
		}else{	
		$purl=C('HTTP_URL').U('Login/first',array('token'=>$this->token,'wecha_id'=>$this->data['FromUserName']));
		$return=" 您好，感谢您关注四川省双流县地方税务局。<a href='".$purl."'>点击这里</a>进行注册";
		}
		}
		return $return;
	}
	//会员预约
	function member_ordername(){
		$where['wecha_id'] = $this->data['FromUserName'];
		$where['status'] = array('in','1,2');
		$From = D('user');
		$user = $From->where($where)->find();
		if($user){
			if($user['status']==1){
			$return="您好，感谢您关注四川省双流县地方税务局。预约<a href='".C('HTTP_URL').U('Order/first',array('token'=>$this->token,'wecha_id'=>$this->data['FromUserName']))."'>点击这里进入</a>";
			}else{
			$return="您好，感谢您关注四川省双流县地方税务局。你需要<a href='".C('HTTP_URL').U('Login/login',array('token'=>$this->token,'wecha_id'=>$this->data['FromUserName']))."'>点击这里</a>先登录，才能预约";
			}
		}else{
		$From1 = D('company');
		$com = $From1->field('company_id','company_name','company_phone','company_tax','company_class','tax_class')->where($where)->find();
		if($com){
					if($com['status']==1){
					$return="您好，感谢您关注四川省双流县地方税务局。预约<a href='".C('HTTP_URL').U('Order/first',array('token'=>$this->token,'wecha_id'=>$this->data['FromUserName']))."'>点击这里进入</a>";
					}else{
						$return="您好，感谢您关注四川省双流县地方税务局。你需要<a href='".C('HTTP_URL').U('Login/login',array('token'=>$this->token,'wecha_id'=>$this->data['FromUserName']))."'>点击这里</a>先登录，才能预约";
					}
					
				}else{
		$purl=C('HTTP_URL').U('Login/first',array('token'=>$this->token,'wecha_id'=>$this->data['FromUserName']));
		$return="您好，感谢您关注四川省双流县地方税务局。您还未注册 <a href='".$purl."'>点击这里</a>进行注册";
		}
		}
		return $return;
	}
	
	
	
	//会员处理-绑定
	function member_bind($id=''){
		$date['wecha_id']=$this->data['FromUserName'];
		$From=D("Rm_vip_info_wx");
		$purl=C('HTTP_URL').U('Login/index',array('token'=>$this->token,'wecha_id'=>$this->data['FromUserName']));
		$rid=$From->field("wecha_id,id,uid")->where($date)->find();
		if($rid){
			$return="亲,您已经绑定微信,不必重复绑定哦！<a href='".C('HTTP_URL').U('Member/index',array('token'=>$this->token,'wecha_id'=>$this->data['FromUserName']))."'>点击进入个人中心</a>";
		}else{
			$purl=C('HTTP_URL').U('Login/index',array('token'=>$this->token,'wecha_id'=>$this->data['FromUserName']));
			$return="亲,您还未绑定微信哦！会员登录后即可绑定. <a href='".$purl."'>我是会员,立即登录绑定</a>";
		}
		return $return;
	}
	
	
	
	//用于测试
	function insert_into($str){
		if(is_array($str)){
			foreach($str as $key =>$val){
				$strstr.=$key.":".$val."<br/>";
			}
		}else{
			$strstr=$str;
		}
		if($strstr){
			$From=D("Mydebug");
			$date['str']=$strstr;
			$From->add($date);
		}
	}
	
	function check_weixin_user($str=''){
		$_SESSION['wecha_id']=$str;
		$where['wecha_id']=$str;
		$From=D("Rm_vip_info_wx");
		$rid=$From->field("wecha_id,id,uid")->where($where)->find();
		if($rid){
			$_SESSION['member_id']=$rid['uid'];
		}
	}
	
	public function requestdata($field) 

	{
		$data ['year'] = date ( 'Y' );
		
		$data ['month'] = date ( 'm' );
		
		$data ['day'] = date ( 'd' );
		
		//$data ['token'] = $this->token;
		
		$mysql = M ( 'Requestdata_in' );
		
		$check = $mysql->field ( 'id' )->where ( $data )->find ();
		
		if ($check == false) {
			
			$data ['time'] = time ();
			
			$data [$field] = 1;
			
			$mysql->add ( $data );
		} else {
			
			$mysql->where ( $data )->setInc ( $field );
		}
	}
	
	public function requestdata_($type='',$methods=''){
		$data ['year'] = date ( 'Y' );	
		$data ['month'] = date ( 'm' );		
		$data ['day'] = date ( 'd' );
		$data ['type'] = $type;	
		$data ['methods'] = $methods;		
		$data ['wecha_id'] = $this->data['FromUserName'];
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
?>