<?php
namespace Sys\Controller;
use Think\Controller;
class WeixinController extends SysadminController {
    public function index(){
		$vo=D("Weixin_secret")->find();
		$this->assign("vo",$vo);
		$this->display();
    }
	
	public function server(){
		$vo=D("Weixin_secret")->field("token")->find();
		$this->assign("vo",$vo);
		$this->display();
    }
	
	function insert($id=''){
		$Form=M('Weixin_secret');
		if(!$Form->create()) {
			header("Content-Type:text/html; charset=utf-8");
			$this->error('创建信息数据库信息失败！');
		}
		if(empty($id)){
			if(false !==$Form->add()) {
				$this->success('数据添加成功！');
			}else{
				$this->error('数据写入错误');
			}
		}else{
			if($Form->save()){
				$this->success('数据修改成功！');
			}else{
				$this->error('数据修改错误');
			}
		}
	}
	
	function up_date_Secret($id='',$appid='',$appsecret=''){
		if(empty($appid) || empty($appsecret)){
			$this->error("参数错误！");
		}
		$access_token=self::Get_access_token($appid,$appsecret);
		if(empty($access_token)){
			$this->error("无效的AppId或AppSecret！");
		}
		$Form	=	M('Weixin_secret');
		if(!$Form->create()) {
			header("Content-Type:text/html; charset=utf-8");
			$this->error('发生未知的错误！');
		}
		$Form->access_token=$access_token;
		if( empty($id))
		{
			if(false !==$Form->add()) {
				$this->success('数据添加成功！');
			}else{
				$this->error('数据添加失败！');
			}
		}
		else
		{
			if($Form->save()){
				$this->success('数据修改成功！');
			}else{
				$this->error('数据修改失败！');
			}
			
		}
	}
	
	//获取access_token
	function Get_access_token($appid='',$secret=''){
		$access_token       = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$secret");
		echo $access_token.'<br>';
		$access_tokendata   = json_decode($access_token,true);
		echo $access_tokendata.'<br>';
		exit;
        return $access_tokendata['access_token'];
	}
	
	function nothing(){
		$From=D("Weixin_key");
		$where['method']='vacant';
		$Form->wxkey='vacant';
		$vo=$From->where($where)->find();
		$this->assign("vo",$vo);
		$this->display();
	}
	
	function update_nothing($id='') {
		$Form	=	M('Weixin_key');
		if(!$Form->create()) {
			header("Content-Type:text/html; charset=utf-8");
			$this->error('发生未知的错误！');
		}
		if(empty($id))
		{
			$Form->method='vacant';
			$Form->wxkey='vacant';
			if(false !==$Form->add()) {
				$this->success('数据添加成功！');
			}else{
				$this->error('数据添加失败！');
			}
		}
		else
		{
			if($delimg!=$Form ->picurl){
				@unlink(".".$_POST['delimg']);
			}
			if($Form->save()){
				$this->success('数据修改成功！');
			}else{
				$this->error('数据修改失败！');
			}
			
		}
	}
	function unsubscribe(){
		$From=D("Weixin_key");
		$where['method']='unsubscribe';
		$Form->wxkey='unsubscribe';
		$vo=$From->where($where)->find();
		$this->assign("vo",$vo);
		$this->display();
	}
	
	function update_unsubscribe($id='') {
		$Form	=	M('Weixin_key');
		if(!$Form->create()) {
			header("Content-Type:text/html; charset=utf-8");
			$this->error('发生未知的错误！');
		}
		if(empty($id))
		{
			$Form->method='unsubscribe';
			$Form->wxkey='unsubscribe';
			if(false !==$Form->add()) {
				$this->success('数据添加成功！');
			}else{
				$this->error('数据添加失败！');
			}
		}
		else
		{
			if($delimg!=$Form ->picurl){
				@unlink(".".$_POST['delimg']);
			}
			if($Form->save()){
				$this->success('数据修改成功！');
			}else{
				$this->error('数据修改失败！');
			}
			
		}
	}
	function subscribe(){
		$From=D("Weixin_key");
		$where['method']='subscribe';
		$Form->wxkey='subscribe';
		$vo=$From->where($where)->find();
		$this->assign("vo",$vo);
		$this->display();
	}
	
	function update_subscribe($id='') {
		$Form	=	M('Weixin_key');
		if(!$Form->create()) {
			header("Content-Type:text/html; charset=utf-8");
			$this->error('发生未知的错误！');
		}
		if(empty($id))
		{
			$Form->method='subscribe';
			$Form->wxkey='subscribe';
			if(false !==$Form->add()) {
				$this->success('数据添加成功！');
			}else{
				$this->error('数据添加失败！');
			}
		}
		else
		{
			if($delimg!=$Form ->picurl){
				@unlink(".".$_POST['delimg']);
			}
			if($Form->save()){
				$this->success('数据修改成功！');
			}else{
				$this->error('数据修改失败！');
			}
			
		}
	}
	
	function diy(){
		$where['aid']=0;
		$where['is_diymenu']='1';
		//$where['is_menu']='0';
		$From=D("Weixin_menu");
		$list=$From->where($where)->order("order1 asc")->select();
		//echo $From->getlastsql();
		foreach($list as $key => $val){
			$wheress['aid']=$val['id'];
			$wheress['is_diymenu']='1';
			//$wheress['is_menu']='0';
			$list[$key]['voo']=$From->where($wheress)->order("order1 asc")->select();
		}
		$this->assign("list",$list);
		$this->display();
	}
	
	function add_diy($id=''){
		$From=D("Weixin_menu");
		if($id){
			$vo=$From->find($id);
			$this->assign("vo",$vo);
		}
		
		$where['aid']=0;
		$where['is_diymenu']='1';
		$list=$From->where($where)->select();
		$this->assign("list",$list);
		$this->display();
	}
	
	function update_diy($id='') {
		$Form	=	M('Weixin_menu');
		if(!$Form->create()) {
			header("Content-Type:text/html; charset=utf-8");
			$this->error('发生未知的错误！');
		}
		if(empty($id))
		{
			$Form->is_diymenu='1';
			$Form->is_menu='0';
			if(false !==$Form->add()) {
				$this->success('数据添加成功！',U('diy'));
			}else{
				$this->error('数据添加失败！');
			}
		}
		else
		{
			if($delimg!=$Form ->picurl){
				@unlink(".".$_POST['delimg']);
			}
			if($Form->save()){
				$this->success('数据修改成功！',U('diy'));
			}else{
				$this->error('数据修改失败！');
			}
			
		}
	}
	
	public function del_diy($id=''){
        empty($id) && $this->error('参数错误！');

        $Model = D('Weixin_menu');
        //删除属性数据
        $res = $Model->delete($id);
        if(!$res){
            $this->error("删除失败");
        }else{
            //记录行为
            $this->success('删除成功');
        }
    }
	
	function creat_menu(){
		$api=D("Weixin_secret")->field("appid,appsecret,access_token,token")->find(array('id'=>1));
		if(empty($api['access_token'])){
			$this->error('【AppId】和【 AppSecret】没有认证哦');exit;
		}
		$url_get='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$api['appid'].'&secret='.$api['appsecret'];
		$json=json_decode(file_get_contents($url_get));
		if($json->errcode=="40001"){$this->error('【AppId】或者【 AppSecret】验证错误');exit;}
		$where["aid"]=0;
		$where["is_menu"]="0";
		$where["is_diymenu"]="1";
		$where["is_show"]="1";
		$F_from=M('Weixin_menu');
		
		//$list=$F_from->where($where)->order("order1 desc")->limit(3)->select();
		$data = '{"button":[';
		$class=$F_from->where($where)->field("id,title,picurl,url,method,fun")->limit(3)->order('order1 asc')->select();//dump($class);
		$kcount=$F_from->where($where)->limit(3)->order('order1 asc')->count();
		$k=1;
		foreach($class as $key=>$vo){
			//主菜单
		    $data.='{"name":"'.$vo['title'].'",';
		    $wheres["aid"]=$vo['id'];
			$wheres["is_menu"]="0";
			$wheres["is_diymenu"]="1";
			$wheres["is_show"]="1";
			$c=$F_from->where($wheres)->field("id,title,picurl,url,method,fun")->limit(5)->order('order1 asc')->select();
			//dump($c);
			$count=$F_from->where($wheres)->limit(5)->order('order1 asc')->count();
				//子菜单
				if($c!=false){
					$data.='"sub_button":[';
				}else{
					if ($vo['url']) {
						//$url=$vo['url'];
						$url=C('HTTP_URL')."/Index/showurl/url/".$vo['id']."~".base64_encode($vo['url']);
						$data.='"type":"view","url":"'.$url.'"';
					}else{
						$data.='"type":"click","key":"'.$vo['method'].'_'.$vo['id'].'"';
					}
				}
				$i=1;
				foreach($c as $voo){
					$voo['url']=str_replace(array('&amp;'),array('&'),$voo['url']);
					if($i==$count){
						if($voo['url']){
							//$url=$voo['url'];
							$url=C('HTTP_URL')."/Index/showurl/url/".$voo['id']."~".base64_encode($voo['url']);
							$data.='{"type":"view","name":"'.$voo['title'].'","url":"'.$url.'"}';
						}else{
							$data.='{"type":"click","name":"'.$voo['title'].'","key":"'.$voo['method'].'_'.$voo['id'].'_'.$voo['fun'].'"}';
						}
					}else{
						if($voo['url']){
							//$url=$voo['url'];
							$url=C('HTTP_URL')."/Index/showurl/url/".$voo['id']."~".base64_encode($voo['url']);
							$data.='{"type":"view","name":"'.$voo['title'].'","url":"'.$url.'"},';
						}else{
							$data.='{"type":"click","name":"'.$voo['title'].'","key":"'.$voo['method'].'_'.$voo['id'].'_'.$voo['fun'].'"},';
						}
					}
					$i++;
				}
				if($c!=false){
					$data.=']';
				}

				if($k==$kcount){
					$data.='}';
				}else{
					$data.='},';
				}
				$k++;
			}
			$data.=']}';
		//$date_menu=trim($date_menu,',');
		//$date_menus='{"button":['.$date_menu.']}';
		//dump($date_menus);exit;
		//封装数据完成！
		file_get_contents('https://api.weixin.qq.com/cgi-bin/menu/delete?access_token='.$json->access_token);//删除原来定义菜单
		
		$url='https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$json->access_token;
		$result=json_decode($this->api_notice_increment($url,$data));
		if($result->errcode!="0"){
			//$this->error("操作失败.如果多次失败，请把开发者凭据再次提交");
			$this->error("操作失败.");
		}else{
			$this->success("操作成功！");
		}
		exit;
	}
	
	function api_notice_increment($url, $data){
		$urlarr = parse_url($url);
		$errno      = "";
		$errstr     = "";
		$transports = "";
		if($urlarr["scheme"] == "https") {
			$transports = "ssl://";
			$urlarr["port"] = "443";
		} else {
			$transports = "tcp://";
			$urlarr["port"] = "80";
		}
		$newurl = $transports . $urlarr['host'];

		$fp = @fsockopen($newurl, $urlarr['port'], $errno, $errstr, 60);
		if(!$fp) {
			die("ERROR: $errno - $errstr<br />\n");
		} else {
			fputs($fp, "POST ".$urlarr["path"]."?".$urlarr["query"]." HTTP/1.1\r\n");
			fputs($fp, "Host: ".$urlarr["host"]."\r\n");
			fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
			fputs($fp, "Content-length: ".strlen($data)."\r\n");
			fputs($fp, "Connection: close\r\n\r\n");
			fputs($fp, $data . "\r\n\r\n");
			while(!feof($fp)) {
				$receive[] = @fgets($fp, 1024);
			}
			fclose($fp);
			$result = $receive[count($receive) - 1];
			return $result;
		}
	}
}