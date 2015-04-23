<?php 
	namespace Sys\Controller;
	use Think\Controller;
	
	class OrdersetController extends SysadminController{
		//返回预约的列表
		public function index(){
			$model = D('orderset');
			$count = $model->where('so_flag=1')->count();
			$Page = new \Think\Page($count,$size=20);
			$limit = $Page->firstRow.','.$Page->listRows;
			$volist = $model->where('so_flag=1')->limit($Page->firstRow. ',' . $Page->listRows)->order("so_id desc")->select();
			$page_bar = $Page->show();//显示分页导航
			$this->assign('volist',$volist);
			$this->assign('count',$count);
			$this->assign ( "pager_bar", $page_bar);
			$this->display();
		}
		//添加页面
		public function addshow($id=''){
			if($id){
			$where['so_flag'] = 1;
			$where['so_id'] = $id;
			$model = D('orderset');
			$vo = $model->where($where)->find();
			if($vo){
				$this->assign('vo',$vo);
			}
			}
			$this->display();
		}
		
		//添加操作
		public function add(){
			$descript = $_POST['descript'];
			$peoplenum = $_POST['peoplenum'];
			$date = $_POST['sodate'];
			
			$id = $_POST['id'];
			
			$weekarray = array(
				"0"=>"星期日",
				"1"=>"星期一",
				"2"=>"星期二",
				"3"=>"星期三",
				"4"=>"星期四",
				"5"=>"星期五",
				"6"=>"星期六"
			);
			
			$w = date('w',strtotime($date));
			$xingqi = $weekarray[$w];
			
			
			if(empty($peoplenum)){
				$this->error('人数没有填写');
			}
			$times = date('Y-m-d');
			if(empty($date)){
				$this->error('时间没有选择');
			}
			if(!is_numeric($peoplenum)){
				$this->error('人数请填写正确的数字');
			}
			if(strtotime($date)-strtotime($times)<0){
				$this->error('时间选择不对');
			}
			
			$Form = M('orderset');
			$data['so_descript'] = $descript;
			$data['so_date'] = $date;
			$data['so_peoplenum'] = $peoplenum;
			$data['so_xingqi'] = $xingqi;
			if(isset($id) && empty($id)){
			if(self::checkDate($date)){
				$this->error($date.'时间已经设定');
			}
			$data['so_peoplecome'] = 0;
			$data['so_orderpeople'] = 0;
			$data['so_createdate'] = $times;
			$data['so_flag'] = 1;//1是正常，2是删除
			if($Form->add($data)){
				$this->success('添加成功');
			}else{
				$this->error('添加失败');
			}
			}else{
				$where['so_id'] = $id;
				if(false !==$Form->where($where)->save($data)){
					$this->success('数据修改成功！',U('Orderset/index'),'3');
				}else{
				$this->error('数据修改错误');
				}
			}
		}
		
		
		

		public function addorder(){
			$model = M('orderadd');
			$vo = $model->find();
			$this->assign('vo',$vo);
			$this->display();
		
		}
		
		public function addorderdo(){
			$id = $_POST['id'];
			$day = $_POST['daynum'];
			$num = $_POST['num'];
			$data['orderadd_day'] = $day;
			$data['orderadd_num'] = $num;
			$model = M('orderadd');
			
				$where['orderadd_id'] = $id;
				$model->where($where)->save($data);
				$this->success("修改成功");
			
		}
		
		
		public function addorder1(){
			$model = M('oadd');
			$vo = $model->find();
			$this->assign('vo',$vo);
			$this->display();
		
		}
		
		public function addorderdo1(){
			$id = $_POST['id'];
			$num = $_POST['num'];
			
			$data['oadd_num'] = $num;
			$model = M('oadd');
		
				$where['oadd_id'] = $id;
				$model->where($where)->save($data);
				$this->success("修改成功");
			
		}
		
		function checkDate($date){
			$model = D('orderset');
			$data['so_date'] = $date;
			
			$oset = $model->where($data)->find();
			if($oset){
			
				return true;
			}else{
				return false;
			}
		
		}
	}

?>