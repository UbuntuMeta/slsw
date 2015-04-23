<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class ArticleController extends Controller {
    public function index(){
		$From=D("Article");
		$where['is_show']='1';
		$list=$From->where($where)->order("id desc")->limit(10)->select();
		$this->assign("list",$list);
		$this->display();
    }
	
	function lists($p=''){
		$From=D("Article");
		$where['is_show']='1';
		$list=$From->where($where)->order("id desc")->limit($p*10,10)->select();
		foreach($list as $key => $val){
			$pic=C('HTTP_URL').$val['pic'];
			$connet.='<li><a href="'.U('show',array('id'=>$val['id'])).'"><div class="img" onerror="this.src=\'/Public/static/images/nopic.jpg\'"><img src="'.$pic.'"/></div><h2>'.$val['title'].'</h2><p class="onlyheight">发布时间:'.$val['addtime'].'</p></a></li>';
		}
        $data['content'] = $connet;//$From->getlastsql();
		$this->ajaxReturn($data);
	}
	
	function show($id=''){
		if(empty($id)){
			$this->show("非法操作哦！");
		}
		$From=D("Article");
		$vo=$From->find($id);
		$this->assign("vo",$vo);
		$this->display();
	}
	
	
	//品牌介绍
	function brand(){
		$this->display();
	}
}