<?php
namespace Sys\Controller;
use Think\Controller;
class OrderController extends Controller {
	public function index(){
		$mydate1 = $_POST['mydate1'];
		$mydate2 = $_POST['mydate2'];
		if(!empty($mydate1) && !empty($mydate2)){
		$data['order_date'] = array('between',array($mydate1,$mydate2));
		}
		if(!empty($mydate2) && empty($mydate1)){
		$data['order_date'] = array('elt',$mydate2);
		}
		if(!empty($mydate1) && empty($mydate2)){
		$data['order_date'] = array('egt',$mydate1);
		}
		
		$model = M('order');
		$data['order_status']=array('neq',5);
		$count = $model->where($data)->count();
		$Page = new \Think\Page($count,$size=20);
		$limit = $page->firstRow.','.$Page->listRows;
		$volist = $model->where($data)->limit($Page->firstRow. ',' . $Page->listRows)->order("order_id desc")->select();
		
		for($i=0;$i<count($volist);$i++){
			$model1 = M('business');
			$data1['business_id'] = $volist[$i]['order_business'];
			$data1['business_flas'] = 1;
			$business_name = $model1->where($data1)->find();
			$volist[$i]['business_name'] = $business_name['business_name'];
			$user = self::getUser($volist[$i]['wecha_id']);
			
			if($volist[$i]['people_class']==1){
				$user = self::getUser($volist[$i]['wecha_id']);
				$volist[$i]['cclass'] = '个人';
				$volist[$i]['phone'] = $user['user_phone'];
				$volist[$i]['name'] = $user['user_name'];
			}
			if($volist[$i]['people_class']==2){
				$user = self::getCompany($volist[$i]['wecha_id']);
				if(empty($user['company_class'])){
					$volist[$i]['cclass'] = '企业';
				}else{
					$volist[$i]['cclass'] = '个体';
					$volist[$i]['company_class'] = $user['company_class'];
				}
				$volist[$i]['phone'] = $user['company_phone'];
				$volist[$i]['name'] = $user['company_name'];
			}
		}
		
		$page_bar = $Page->show();//显示分页导航
		
		$url = U('orderexcel',array('mydate1'=>$mydate1,'mydate2'=>$mydate2));
		$this->assign('purl',$url);
		$this->assign('volist',$volist);
		$this->assign('count',$count);
		$this->assign ( "pager_bar", $page_bar);
		$this->display();
	}
	
	
	public function update(){
		$data['order_id'] = $_POST['order_id'];
		$where['order_status'] = $_POST['status'];
		$model = M('order');
		$order = $model->where($data)->find();
		if($order['order_status'] == $_POST['status']){
			$this->error('你已经修改过了');
		}
		$vo = $model->where($data)->save($where);
		
		if($vo){
			$model1 = M('orderset');
			$da['so_id'] = $order['so_id'];
			if($_POST['status'] == 2){
				$vo1 = $model1->where($da)->setInc('so_peoplecome');
			}else if($_POST['status'] == 4){
				$vo1 = $model1->where($da)->setDec('so_orderpeople');
			}else if($_POST['status'] == 5){
				$vo1 = $model1->where($da)->setDec('so_orderpeople');
			}
			$this->success('修改成功!');
		}else{
			$this->error('修改失败');
		}
	}
	
	public function orderexcel(){
		$mydate1 = $_GET['mydate1'];
		$mydate2 = $_GET['mydate2'];
		if(!empty($mydate1) && !empty($mydate2)){
		$data['order_date'] = array('between',array($mydate1,$mydate2));
		}
		if(!empty($mydate2) && empty($mydate1)){
		$data['order_date'] = array('elt',$mydate2);
		}
		if(!empty($mydate1) && empty($mydate2)){
		$data['order_date'] = array('egt',$mydate1);
		}
		
		$model = M('order');
		$data['order_status']=array('neq',5);
		$volist = $model->where($data)->order("order_date desc")->select();
		
		for($i=0;$i<count($volist);$i++){
			$model1 = M('business');
			$data1['business_id'] = $volist[$i]['order_business'];
			$data1['business_flas'] = 1;
			$business_name = $model1->where($data1)->find();
			$volist[$i]['business_name'] = $business_name['business_name'];
			$user = self::getUser($volist[$i]['wecha_id']);
			if($volist[$i]['order_status'] == 1){
				$volist[$i]['order_status_name'] = '已预约';
			}
			if($volist[$i]['order_status'] == 2){
				$volist[$i]['order_status_name'] = '已处理';
			}
			if($volist[$i]['order_status'] == 3){
				$volist[$i]['order_status_name'] = '未到';
			}
			if($volist[$i]['order_status'] == 4){
				$volist[$i]['order_status_name'] = '已取消';
			}
			if($volist[$i]['people_class']==1){
				$user = self::getUser($volist[$i]['wecha_id']);
				$volist[$i]['cclass'] = '个人';
				$volist[$i]['phone'] = $user['user_phone'];
				$volist[$i]['name'] = $user['user_name'];
			}
			if($volist[$i]['people_class']==2){
				$user = self::getCompany($volist[$i]['wecha_id']);
				if(empty($user['company_class'])){
					$volist[$i]['cclass'] = '企业';
				}else{
					$volist[$i]['cclass'] = '个体';
					$volist[$i]['company_class'] = $user['company_class'];
				}
				$volist[$i]['phone'] = $user['company_phone'];
				$volist[$i]['name'] = $user['company_name'];
			}
		}
		//导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
		import("Org.Util.PHPExcel");
		import("Org.Util.PHPExcel.Writer.Excel5");
		import("Org.Util.PHPExcel.IOFactory.php");
		$filename = 'test';
		$headerArr = array("编号","预约时间","营销税申报","预约用户名","会员手机号","会员类型",
		"会员名","公司类型","预约号","预约状态");
		$this->getExcel($filename,$headerArr,$volist);
		
	}
	
	private function getExcel($fileName,$headArr,$data){
		//对数据进行检验
		    if(empty($data) || !is_array($data)){
		        die("data must be a array");
		    }
		    //检查文件名
		    if(empty($fileName)){
		        exit;
		    }
			import("Org.Util.PHPExcel");
			import("Org.Util.PHPExcel.Writer.Excel5");
			import("Org.Util.PHPExcel.IOFactory.php");
			$date = date("Y_m_d",time());
		    $fileName .= "_{$date}.xls";
			//创建PHPExcel对象，注意，不能少了\
		    $objPHPExcel = new \PHPExcel();
		    $objProps = $objPHPExcel->getProperties();
			//设置表头
		    $key = ord("A");
		    foreach($headArr as $v){
		        $colum = chr($key);
		        $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
		        $key += 1;
		    }
			$column = 2;
		    $objActSheet = $objPHPExcel->getActiveSheet();
		    for($i=0;$i<count($data);$i++){ 
			//行写入
			 $objPHPExcel->getActiveSheet(0)->setCellValue('A'.($i+2),$data[$i]['order_id']);
		     $objPHPExcel->getActiveSheet(0)->setCellValue('B'.($i+2),$data[$i]['order_date']);
			 $objPHPExcel->getActiveSheet(0)->setCellValue('C'.($i+2),$data[$i]['business_name']);
			 $objPHPExcel->getActiveSheet(0)->setCellValue('D'.($i+2),$data[$i]['user_name']);
			 $objPHPExcel->getActiveSheet(0)->setCellValue('E'.($i+2),$data[$i]['phone']);
			$objPHPExcel->getActiveSheet(0)->setCellValue('F'.($i+2),$data[$i]['cclass']);
			$objPHPExcel->getActiveSheet(0)->setCellValue('G'.($i+2),$data[$i]['name']);
			$objPHPExcel->getActiveSheet(0)->setCellValue('H'.($i+2),$data[$i]['company_class']);
			$objPHPExcel->getActiveSheet(0)->setCellValue('I'.($i+2),$data[$i]['order_number']);
			$objPHPExcel->getActiveSheet(0)->setCellValue('J'.($i+2),$data[$i]['order_status_name']);
					 
			}
			 $fileName = iconv("utf-8", "gb2312", $fileName);
		    //重命名表
		   	// $objPHPExcel->getActiveSheet()->setTitle('test');
		    //设置活动单指数到第一个表,所以Excel打开这是第一个表
		    $objPHPExcel->setActiveSheetIndex(0);
		    header('Content-Type: application/vnd.ms-excel');
			header("Content-Disposition: attachment;filename=\"$fileName\"");
			header('Cache-Control: max-age=0');

		  	$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		    $objWriter->save('php://output'); //文件通过浏览器下载
		    exit;
			
			
			
	}
	
	
	
	function getUser($wecha_id = ''){
		$user = M('user');
		$where['wecha_id'] = $wecha_id;
		$where['status'] = 1;
		$userinfo = $user->where($where)->find();
			
		if($user){
			$vo = $userinfo;
		}
		return $vo;
	
	}
	
	function getCompany($wecha_id = ''){
		$user = M('company');
		$where['wecha_id'] = $wecha_id;
		$where['status'] = 1;
		$userinfo = $user->where($where)->find();
		if($user){
			$vo = $userinfo;
		}
		return $vo;
	
	}
	
	
	
	
	/* public function orderfind(){
		$mydate1 = $_POST['mydate1'];
		$mydate2 = $_POST['mydate2'];
		$order = M('order');
		$times = date('Y-m-d');
		$data['order_status']=array('neq',5);
		$data['order_date'] = array('egt',$mydate1);
		$data['order_date'] = array('elt',$mydate2);
		$count = $order->where($data)->count();
		$Page = new \Think\Page($count,$size=20);
		$limit = $Page->firstRow.','.$Page->listRows;
		$volist = $model->where($date)->limit($Page->firstRow. ',' . $Page->listRows)->order("so_id desc")->select();
		$page_bar = $Page->show();//显示分页导航
		$this->assign('volist',$volist);
		$this->assign('count',$count);
		$this->assign ( "pager_bar", $page_bar);
		$this->assign('mydate1',$mydate1);
		$this->assign('mydate2',$mydate2);
		$this->display('index');
	} */
	
}
?>