<?php
namespace Sys\Controller;
use Think\Controller;
class ProductController extends SysadminController {
   public function index() {
		$db=D("Article");
		//$where['articletype']='news';
		$count=$db->where($where)->count();
		$Page = new \Think\Page($count, $size=10); //count总数 $size每页显示数
		$limit = $Page->firstRow . ',' . $Page->listRows;
		$list=$db->where($where)->limit($Page->firstRow. ',' . $Page->listRows)->order("id desc")->select();
		$this->pager_bar = $Page->show(); //显示分页导航
		$this->assign('list',$list);
		$this->display();
	}

	public function add($id='') {
		$Froms=D("Weixin_menu");		
		$where['aid']=0;
		$where['is_diymenu']='1';
		$list=$Froms->where($where)->select();
		$this->assign("list",$list);
		
		$From=D("Article");
		//$where['pid']='0';
//		$left_list=$From->where($where)->select();
////		foreach($left_list as $key => $val){
////			$where="pid=".$val['id'];
////			$left_list[$key]['voo']=$From->where("$where")->select();
////		}
//		$this->assign("lm_list",$left_list);
		if($id){
			$vo=$From->find($id);
			$this->assign("vo",$vo);
		}
		$this->display();
	}
	
	function insert($id=''){
		 //echo $id; exit;
		$From=D("Article");
		if(!$From->create()) {
			header("Content-Type:text/html; charset=utf-8");
			$this->error('创建信息数据库信息失败！');
		}
		if(isset($id) && empty($id)){
			$From->articletype='news';
			$From->addtime=date('Y-m-d H:i:s');
			if(false !==$From->add()) {
				//$this->assign('jumpUrl','/Menu/add');
				$this->success('数据添加成功！',U('Article/add'),'3');
			}else{
				$this->error('数据写入错误');
			}
		}else{
			if(false !==$From->save()) {
				//$this->assign('jumpUrl','/Menu/add');
				$this->success('数据修改成功！',U('Article/index'),'3');
			}else{
				$this->error('数据修改错误');
			}
		}
	}
	
	public function del($id=''){
        empty($id) && $this->error('参数错误！');

        $Model = D('Article');
        //删除属性数据
        $res = $Model->delete($id);
        if(!$res){
            $this->error("删除失败");
        }else{
            //记录行为
            $this->success('删除成功', U('index'));
        }
    }
}