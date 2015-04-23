<?php
	namespace Sys\Controller;
	use Think\Controller;
	class BusinessController extends Controller{
		public function add(){
			/* $result=self::MakeTree('0');  */
		//dump($result);
			/* dump($result);
			$this->assign('list',$result); */
			
			$model = M('business');
			$where['business_flas'] = 0;
			$where2['business_flas']=1;
		
			
			$model= M('business');
			$b_list = $model->where($where2)->order('business_cls_parent asc,business_value asc')->select();
			
	
			/* for($i=0;$i<count($b_list);$i++){
				$where1['business_cls_parent'] = $b_list[$i]['business_id'];
				$b_list[$i]['voo'] = $model->where("$where1")->select();
				
				
				for($j=0;$j<count($b_list[$i]['voo']);$j++){
					$where2['business_cls_parent'] = $b_list[$i][$j]['business_id'];
					$b_list[$i][$j]['voo']['voo1'] = $model->where("$where2")->select();
					echo $model->getLastSql();
				}
			
			} */
			
			
			$this->assign('b_list',$b_list);
			$this->display();
			
		}
		
		public function adddo(){
			$name = $_POST['business_name'];
			$content = $_POST['business_content'];
			$parent = $_POST['business_cls_parent'];
			$value = $_POST['business_value'];
			if(empty($name)){
				$this->error('失败');
			}
			$data['business_name'] = $name;
			$data['business_cls_parent'] = 	$parent;
			$data['business_content'] = $content;
			$data['business_value'] = $value;
			$model = M("business");
			if($model->add($data)){
				$this->success("成功!");
			}else{
				$this->error("失败!");
			}
		}		
		
		public function index(){
			$model = M("business");
			$where['business_flas'] = 1;
			$count = $model->where($where)->count();
			$Page = new \Think\Page($count,$size=20);
			$limit = $page->firstRow.','.$Page->listRows;
			$volist = $model->where($where)->limit($Page->firstRow. ',' . $Page->listRows)->order('business_cls_parent desc,business_value asc')->select();
			for($i=0;$i<count($volist);$i++){
				$where1['business_id'] = $volist[$i]['business_cls_parent'];
				$where1['business_flas'] = 1;
				$vo = $model->where($where1)->find();
				$volist[$i]['business_parent_name'] = $vo['business_name'];
			}
			$page_bar = $Page->show();//显示分页导航
			$this->assign ( "pager_bar", $page_bar);
			$this->assign('volist',$volist);
			$this->display();
		}
		
		/* public function index(){
			$model = M("business");
			$where['business_cls_parent'] = 0;
			$where['business_flas'] = 1;
			$count = $model->where($where)->count();
			$Page = new \Think\Page($count,$size=5);
			$limit = $page->firstRow.','.$Page->listRows;
			$volist = $model->where($where)->limit($Page->firstRow. ',' . $Page->listRows)->order('business_id asc')->select();
			foreach($volist as $key => $val){
			$wheress['business_cls_parent']=$val['business_id'];
			$wheress['business_flas']='1';
			//$wheress['is_menu']='0';
			$list[$key]['voo']=$model->where($wheress)->order("business_id asc")->select();
			foreach($list[$key]['voo'] as $key1 => $val){
				$wheress['business_cls_parent']=$val['business_id'];
				$wheress['business_flas']='1';
			//$wheress['is_menu']='0';
				$list[$key1][$key]['voo']=$model->where($wheress)->order("business_id asc")->select();
			}
			}
			$this->assign("list",$list);
			$page_bar = $Page->show();//显示分页导航
			$this->assign ( "pager_bar", $page_bar);
			$this->display();
			
		} */
		
		
		
		
		public function delete($id=''){

			$id=$_GET['id'];
			$model = M("business");
			$res = $model->delete($id);
			if(!$res){
            $this->error("删除失败");
			}else{
            //记录行为
            $this->success('删除成功', U('index'));
			}
		
		}
		
		public function update($id=''){
			/* $where2['business_cls_parent'] = 0; */
			$where2['business_flas']=1;
		
			
			$model= M('business');
			$b_list = $model->where($where2)->order('business_cls_parent asc,business_value asc')->select();
			/* foreach($b_list as $key =>$val){
				$where3="business_cls_parent=".$val['business_id'];
				$b_list[$key]['voo'] = $model->where("$where3")->select();
				foreach($b_list[$key]['voo'] as $key1=>$val1){
					$where4="business_cls_parent=".$val1['business_id'];
					$b_list[$key1][$key]['voo']['voo1'] = $model->where("$where4")->select();
				}
			} */
			$where['business_id'] = $id;
			$where['business_flas'] = 1;
			$vo = $model->where($where)->find();
			$where1['business_id'] = $vo['business_cls_parent'];
			$where1['business_flas'] = 1;
			$vo1 = $model->where($where1)->find();
			$vo['business_parent_name'] = $vo1['business_name'];
			
			$this->assign('b_list',$b_list);
			$this->assign('vo',$vo);
			$this->display();
		
		
		}
		
		
		public function updatedo(){
			$name = $_POST['business_name'];
			$parent = $_POST['business_cls_parent'];
			$content = $_POST['business_content'];
			$value = $_POST['business_value'];
			$id = $_POST['business_id'];
			if(empty($name)){
				$this->error('失败');
			}
			$data['business_name'] = $name;
			$data['business_cls_parent'] = 	$parent;
			$data['business_content'] = $content;
			$where['business_id'] = $id;
			$data['business_value'] = $value;
			$model = M("business");
			$query = $model->where($where)->save($data);
			if($query){
				$this->success("成功!");
			}else{
				$this->error("失败!");
			}
		}
		
		
		
		
		function MakeTree($id=''){
			echo 'id'.$id;
			$model = M('business');
			$where['business_cls_parent'] = $id;
			$where['business_flas'] = 1;
			$result = $model->where($where)->select();
			 echo $model->getLastSql();
			if($result){
			echo '1';
				 foreach ($result as $key => $value){
					$title = $value['alias'];
					$list[$title]['business_id'] = $value['business_id'];
					$list[$title]['business_cls_parent'] = $value['business_cls_parent'];
					
					$list[$title]['business_content'] = $value['business_content'];
					$list[$title]['business_value'] = $value['business_value'];
					if($this->haschild($value['business_id'])){  
					//先判断是否有第三级子类,最后的数组形如$result['child']['grandchild']; 
					echo "aa";
					$list[$title]['grandchild']=self::MakeSonTree($value['business_id']); 
					} 
					
				 }
			}
			return $list;
		}
		
		function MakeSonTree($id='',$name=''){
			$model = M('business');
			$where['business_cls_parent'] = $id;
			$where['business_flas'] = 1;
			$result = $model->where($where)->select();
			
			if($result){
				 foreach ($result as $key => $value){
					$title = $value['alias'];
					$list[$title]['business_id'] = $value['business_id'];
					$list[$title]['business_cls_parent'] = $value['business_cls_parent'];
					$list[$title]['business_parent_name'] = $name;
					$list[$title]['business_content'] = $value['business_content'];
					$list[$title]['business_value'] = $value['business_value'];
					$list[$title]['child'] = $this->MakeSonTree($value['business_id']);
					if($this->haschild($value['business_id'])){     //先判断是否有第三级子类,最后的数组形如$result['child']['grandchild']; 
					$list[$title]['grandchild']=self::MakeSonTree($value['business_id']); 
					} 
				 }
			}
			return $list;
		}
		
		function haschild($id=''){ 
		$where['business_cls_parent'] = $id;
		$where['business_flas'] = 1;
		$result=D('business')->where($where)->find(); 
		if($result){ 
		return true; 
		} 
		else return false; 
		}
	}
?>