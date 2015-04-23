<?php
	namespace Home\Controller;
	use Think\Controller;
	class UploadController extends Controller{
		public function index(){
			$model = M("uploadfile");
			$volist = $model->where('upload_flag')->select();
			$this->assign('volist',$volist);
			$this->display();
		}
	}


?>