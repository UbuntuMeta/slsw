<?php 
	namespace Home\Controller;
	use Think\Controller;
	class OrderController extends UserWxController{
		public function index($number=''){
			
			$data['order_id'] = $number;
			$model = M('order');
			$vo = $model->where($data)->find();
			$business_id = $vo['order_business'];
			$model1= M('business');
			$where['business_id'] = $business_id;
			$where['business_flas'] = 1;
			$bu = $model1->where($where)->find();
			
			$url=U('Member/index',array('token'=>'','wecha_id'=>$_SESSION['wecha_id']));
			$this->assign('url',$url);
			
			$this->assign('business_name',$bu['business_name']);
			$this->assign('vo',$vo);
			
			$this->display();
			
		}
	
	
		public function first($wecha_id=''){
			if($wecha_id){
			$wh['wecha_id'] = $wecha_id;
			$wh['status'] = 1;
			$u = M('user');
			$user = $u->where($wh)->find();
			if($user){
			$model = M('orderset');
			
			$todaydate=date("Y-m-d");
			$size=0;
			$todaydate = date('Y-m-d',strtotime($todaydate.'-'.$size.'day'));
			
			$size = 8;
			$showdate = date('Y-m-d',strtotime($todaydate.'+'.$size.'day'));
			
			$where['so_flag'] = 1;
			$where['so_date'] = array(array('gt',$todaydate),array('lt',$showdate)); 
			$volist = $model->where($where)->order('so_date asc')->select();
			
			}else{
				$c = M('company');
				$com = $c->where($wh)->find();
			if($com){
			$model = M('orderset');
			$w = date('w');
			$todaydate=date("Y-m-d");
			$size=0;
			$todaydate = date('Y-m-d',strtotime($todaydate.'-'.$size.'day'));
			
			$size = 8;
			$showdate = date('Y-m-d',strtotime($todaydate.'+'.$size.'day'));
			
			$where['so_flag'] = 1;
			$where['so_date'] = array(array('gt',$todaydate),array('lt',$showdate)); 
			$volist = $model->where($where)->order('so_date asc')->select();
			
				}
			
			}
			}else{
				redirect(U('Login/first',array('token'=>$token,'wecha_id'=>$wecha_id)));
			}
			
			$this->assign('wecha_id',$wecha_id);
			$this->assign('volist',$volist);
			$this->display();
		}
		
		public function firstdo(){
			$wecha_id = $_POST['wecha_id'];
			$so_id = $_POST['so_id'];
			/* $od = M('orderadd');
			$orderadd = $od->find(); */
			
			
		/* 	$w = date('w');
			$todaydate=date("Y-m-d");
			$size=1;
			$todaydate = date('Y-m-d',strtotime($todaydate.'-'.$size.'day'));
			$size = 7-$w;
			$showdate = date('Y-m-d',strtotime($todaydate.'+'.$size.'day'));
			$where['so_flag'] = 1;
			$where['so_date'] = array(array('gt',$todaydate),array('lt',$showdate)); 
			$where['wecha_id'] = $wecha_id;
			$where['order_status'] = 1;
			$order = M('order');
			$count = $order->where($where)->count();
			
			 */
			$od = M('oadd');
			$oadd = $od->find();
			$count=self::checkOrderweeknum1($wecha_id);
			if($oadd['oadd_num']<=$count){
				$data['content'] = "每周只能预约".$oadd['oadd_num']."次";
				$this->ajaxReturn($data);
			}
			$orderadd = self::checkOrdernum($wecha_id);
			if($orderadd['count']>=$orderadd['orderadd_num']){
				$data['content'] = "在".$orderadd['orderadd_day']."天内只能预约".$orderadd['orderadd_num']."次";
				$this->ajaxReturn($data);
			}
			if(empty($so_id)){
				$data['content'] = "你还没有选择时间";
				$this->ajaxReturn($data);
			}
			$mo = M('orderset');
			$wh['so_id'] = $so_id;
			$wh['so_flag'] = 1;
			$so = $mo->where($wh)->find();
			if($so['so_id']>0){
				if($so['so_peoplenum']<=$so['so_orderpeople']){
					$data['content'] = $so['so_date']."已经预约满了，请您选择其他的时间";
					$this->ajaxReturn($data);
				}else{
					$data['content'] ="你选择的时间是".$so['so_date'].'  '.$so['xingqi'];
					$data['status']  = 1;
					$data['url']=U('order/second',array('wecha_id'=>$wecha_id,'so_id'=>$so_id));
					$this->ajaxReturn($data);
				}
			}else{
				$data['content'] = "你还没有选择时间";
				$this->ajaxReturn($data);
			}
			
			
		}
			
			
		public function second($wecha_id='',$so_id=''){
			$model = M('business');
			$where['business_cls_parent']= 0;
			$where['business_flas'] = 1;
			$blist = $model->where($where)->order('business_value asc')->select();
			$this->assign('blist',$blist);
			
			$this->assign('wecha_id',$wecha_id);
			$this->assign('so_id',$so_id);
			
			$this->display();
			
		}
		public function seconddo(){
			$wecha_id = $_POST['wecha_id'];
			$so_id = $_POST['so_id'];
			$first = $_POST['first'];
			$second = $_POST['second'];
			$thrid = $_POST['thrid'];
			if(!empty($thrid)){
				$business_id = $thrid;
			}else if(!empty($second)){
				$business_id = $second;
			}else{
				$business_id = $first;
			}
			$model = M('business');
			$data['business_flas'] = 1;
			$data['business_cls_parent'] = $business_id;
			$bu = $model->where($data)->find();
			$da['business_flas'] = 1;
			$da['business_id'] = $business_id;
			$business = $model->where($da)->find();
			if($bu){
				$data['content'] = "你还没有选择完业务！";
				$this->ajaxReturn($data);
			}else{
				$data['content'] = "你选择的业务是".$business['business_name'];
				$data['status']  = 1;
				$data['url']=U('order/third',array('wecha_id'=>$wecha_id,'so_id'=>$so_id,'business_id'=>$business_id));
				$this->ajaxReturn($data);		
			}
		}
		public function third($wecha_id='',$so_id='',$business_id=''){
			
			$model = D('business');
			$where['business_id'] = $business_id;
			$b = $model->where($where)->find();
			/* echo 'wecha_id'.$wecha_id.'<br>';
			echo 'so_id'.$so_id.'<br>';
			echo 'business_id'.$business_id.'<br>'; */
			
			$this->assign('so_id',$so_id);
			$this->assign('wecha_id',$wecha_id);
			
			$this->assign('business_id',$business_id);
			$this->assign('business_content',$b['business_content']);
			$this->display();
		}
		
		public function four(){
			$wecha_id = $_POST['wecha_id'];
			$so_id = $_POST['so_id'];
			$od = M('oadd');
			$oadd = $od->find();
			$count=self::checkOrderweeknum1($wecha_id);
			if($oadd['oadd_num']<=$count){
				$data['content'] = "每周只能预约".$oadd['oadd_num']."次";
				$this->ajaxReturn($data);
			}
			$business_id = $_POST['business_id'];
			$model = M('user');
			$where['wecha_id'] = $wecha_id;
			$where['status'] = 1;
			$vo = $model->where($where)->find();
			$user_class = 1;
			if($vo){
				$user_class = 1;
				$user_id = $vo['user_id'];
				$user_name = $vo['user_username'];
			}else{
				$m1 = M('company');
				$vo1 = $m1->where($where)->find();
				$user_id = $vo1['company_id'];
				$user_class = 2;
				$user_name = $vo1['company_name'];
			}
			
			$model2 = M('orderset');
			$where2['so_id'] = $so_id;
			$where2['so_flag'] = 1;
			$so = $model2->where($where2)->find();
			if($so['so_peoplenum']<=$so['so_orderpeople']){
				$data['content'] = "预约已满，请重新选择时间";
				$this->ajaxReturn($data);
			}
			$model5 = M('order');	
			$a = $so['so_date'];
			$a = str_replace('-','',$so['so_date']);
			$where5['so_id'] = $so_id;
			$max = $model5->where($where5)->count();
			$number = $max+1;
			if($max<10){
				$number = $a.'00'.$max;
			}
			if($max<100){
				$number = $a.'0'.$max;
			}
			
			
			$orderadd = self::checkOrdernum($wecha_id);
			if($orderadd['count']<$orderadd['orderadd_num']){
				$da['order_date'] = $so['so_date'];
				$da['order_createdate'] = date("Y-m-d");
				$da['order_number'] = $number;
				$da['order_status'] = 1;
				$da['so_id'] = $so_id;
				$da['order_people'] = $user_id;
				$da['people_class'] = $user_class;
				$da['order_business'] = $business_id;
				$da['wecha_id'] = $wecha_id;
				$da['user_name'] = $user_name;
				$model3 = M('order');
				 $result= $model3->add($da);
				if($result){
					
					$model4 = M('orderset');
					$where3['so_id'] = $so_id;
					
					$da3['so_orderpeople'] = $so['so_orderpeople']+1;
					$model4->where($where3)->save($da3);
					
					$data['content'] = "预约成功";
					$data['status']  = 1;
					$_SESSION['wecha_id'] = $wecha_id;
					$data['url']=U('Order/index',array('number'=>$result));
					$this->ajaxReturn($data);
				}else{
					$data['content'] = "预约失败";
					$this->ajaxReturn($data);
					
					
				}
			}else{
				$data['content'] = "在".$orderadd['orderadd_day']."天内只能预约".$orderadd['orderadd_num']."次";
				$this->ajaxReturn($data);
			
			}
			
			
		}
		
		//检查在时间内预约的未到次敿
		function checkOrdernum($wecha_id=''){
			$odd = M('orderadd');
			$orderadd = $odd->find();
			$todaydate=date("Y-m-d");
			$size=$orderadd['orderadd_day'];
			$todaydate1 = date('Y-m-d',strtotime($todaydate.'-'.$size.'day'));
			$size1 = 1;
			$showdate = date('Y-m-d',strtotime($todaydate.'+'.$size1.'day'));
			$where['so_flag'] = 1;
			$where['so_date'] = array(array('gt',$todaydate),array('lt',$showdate)); 
			$where['wecha_id'] = $wecha_id;
			$where['order_status'] = 3;
			$order = M('order');
			$count = $order->where($where)->count();
			$orderadd['count'] = $count;
			return $orderadd;
			
			
		}
		
		//检查每周限制
		function checkOrderweeknum1($wecha_id=''){
			
			$w = date('w');
			$todaydate=date("Y-m-d");
			$size=1;
			$todaydate = date('Y-m-d',strtotime($todaydate.'-'.$w.'day'));
			$size = 7-$w;
			$showdate = date('Y-m-d',strtotime($todaydate.'+'.$size.'day'));
			
			$where['so_date'] = array(array('gt',$todaydate),array('lt',$showdate)); 
			$where['wecha_id'] = $wecha_id;
			$where['order_status'] = 1;
			$order = M('order');
			$count = $order->where($where)->count();
			return $count;
			
		}
		
		public function ordersecond(){
			$id = $_GET['id'];
			$model = D('business');
			$where['business_cls_parent'] = $id;
			$where['business_flas'] = 1;
			$volist = $model->where($where)->order('business_value asc')->select();
			$string = '';
			if(count($volist)>0){
			
			$string = '<option value="">==选择预约业务==</option>';
			for($i = 0;$i<count($volist);$i++){
				$string = $string."<option value='".$volist[$i]['business_id']."'>".$volist[$i]['business_name']."</option>";
			}
			
			
			}
			header("content-type:text/html;charset=utf-8");
			echo $string;
		}
		
		function checkorderset($so_id=''){
			$model = M('orderset');
			$where['so_id'] = $so_id;
			$where['so_flag'] = 1;
			$so = $model->where($where)->find();
			if($so['so_id']>0){
				if($so['so_peoplenum']<=$so['orderpeople']){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
		
	
		
		
		
	
	}

?>