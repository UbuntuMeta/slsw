<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class WenjuanController extends Controller {
    public function index(){
		$this->assign("wecha_id",$_GET['wecha_id']);
		//$datas['wecha_id']=$_GET['wecha_id'];
		//$From=D("Wenjuan");
		//$sss=$From->field("id")->where($datas)->find();
		//if($sss){
			//$this->show("您已经填写过了,不必重复填写!");
		//}else{
			$this->display();
		//}
    }
	
	function ajaxSend(){
		$datas['wecha_id']=$_POST['wecha_id'];
		$From=D("Wenjuan");
		$data['status']  = 0;
		//if($From->field("id")->where($datas)->find()){
			//$data['content'] = "您已经填写过了,不必重复填写";
			//$this->ajaxReturn($data);
		//}
		
		for($i=1;$i<=13;$i++){
			$field="t".$i;
			$datas[$field]=$_POST[$field];
			if(empty($datas[$field])){
				$data['content'] = "选项 '".$i."' 没有选择哦！";
				$this->ajaxReturn($data);
			}
		}
		$datas['txt7']=$_POST['txt7'];
		$datas['txt8']=$_POST['txt8'];
		$datas['addtime']=date('Y-m-d H:i:s');
		$t1=$_POST['t1'];
		
		$data['content'] = "提交失败！";
		if($From->add($datas)){
			$data['status']  = 1;
			$data['content'] = "提交成功！";
			$data['url'] = U('show');
			$this->ajaxReturn($data);
		}else{
			$this->ajaxReturn($data);
		}
	}
	
	function show(){
		$this->display();
	}
}