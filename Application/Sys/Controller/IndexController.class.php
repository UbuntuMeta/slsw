<?php
namespace Sys\Controller;
use Think\Controller;
class IndexController extends SysadminController {
    public function index(){
		$From=D("authority");
		$where['show1']='1';
		$where['leve']='1';
		$where['pid']='0';
		$toplist=$From->where($where)->order("order1")->select();
		$this->assign("toplist",$toplist);
		$this->display();
    }
	
	public function main() {
		$info = array(
            '操作系统'=>PHP_OS,
            '运行环境'=>$_SERVER["SERVER_SOFTWARE"],
            'PHP运行方式'=>php_sapi_name(),
			'PHP版本'=>phpversion(),
            'ThinkPHP版本'=>THINK_VERSION,
            '上传附件限制'=>ini_get('upload_max_filesize'),
            '执行时间限制'=>ini_get('max_execution_time').'秒',
            '服务器时间'=>date("Y年n月j日 H:i:s"),
            '北京时间'=>gmdate("Y年n月j日 H:i:s",time()+8*3600),
            '服务器域名/IP'=>$_SERVER['SERVER_NAME'].' [ '.gethostbyname($_SERVER['SERVER_NAME']).' ]',
            'register_globals'=>get_cfg_var("register_globals")=="1" ? "ON" : "OFF",
            'magic_quotes_gpc'=>(1===get_magic_quotes_gpc())?'YES':'NO',
            'magic_quotes_runtime'=>(1===get_magic_quotes_runtime())?'YES':'NO',
            );
        $this->assign('info',$info);
		$this->display();
	}
	
	public function menu() {
		if($_SESSION["sysadminmgroupid"]==0)
		   $this->display();
		else
		   $this->display("menu1");
	}
	
	public function load_left(){
		$From=D("authority");
		$where['show1']='1';
		$where['leve']='2';
		$where['pid']=$_GET['menuid'];
		$left_list=$From->where($where)->order("order1")->select();
		foreach($left_list as $key => $val){
			$where="show1='1'and  leve='3' and pid=".$val['id'];
			$left_list[$key]['voo']=$From->where("$where")->select();
		}
		$this->assign("left_list",$left_list);
		$this->display("left");
	}
}