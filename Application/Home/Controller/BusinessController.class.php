<?php
	namespace Home\Controller;
	use Think\Controller;
	class BusinessController extends Controller{
		public function index(){
			$model = M("business");
			$where['business_cls_parent'] = 0;
			$b_list = $model->where($where)->select();
			foreach($b_list as $key =>$val){
				$where="business_cls_parent=".$val['business_id'];
				$b_list[$key]['voo'] = $model->where("$where")->select();
			}
			$volist = $model->select();
			$this->assign('volist',$volist);
			$this->assign('b_list',$b_list);
			$this->display();
			
		}
		
		public function add(){
			$name = $_POST['business_name'];
			$parent = $_POST['business_cls_parent'];
			if(empty($name)){
				$this->error('失败');
			}
			$data['business_name'] = $name;
			$data['business_cls_parent'] = 	$parent;
			$model = M("business");
			if($model->add($data)){
			echo $parent;
		
				$this->success("成功!");
			}else{
				$this->error("失败!");
			}
		}		
		
		public function del(){
			$model = M("business");
			$volist = $model->select();
			$this->assign('volist',$volist);
			$this->display();
		}
		
		public function delete($id=''){

			$id=$_GET['id'];
			$model = M("business");
			$res = $model->delete($id);
			if(!$res){
            $this->error("删除失败");
			}else{
            //记录行为
            $this->success('删除成功', U('del'));
			}
		
		}
	
	}
?>