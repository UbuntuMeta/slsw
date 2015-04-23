<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class ProductController extends Controller {
    public function index($typeid='100',$p=''){
		$url=C('HTTP_API')."/kxapi/product_api.class.php?fun_name=Product&tid=".$typeid."&p=".$p;
		$list = json_decode(file_get_contents($url),TRUE);
		$counts=count($list);
		for($i=0;$i<$counts;$i++){
			$array1=explode("|",$list[$i]['pic']);
			if(is_array($array1)){
				if((strpos($array1[0],"http://")===false) || (strpos($array1[0],"https://")===false)){
					$list[$i]['pic']="http://shop.zhuyeqing-tea.com/".$array1[0];
				}else{
					$list[$i]['pic']=$array1[0];
				}
			}
		}
		$this->assign("list",$list);
		$this->display();
    }
	
	function show($id=''){
		$url=C('HTTP_API')."/kxapi/product_api.class.php?fun_name=ProductOne&tid=".$id;
		$vo = json_decode(file_get_contents($url),TRUE);
		$array1=explode("|",$vo[0]['small_pic']);
		$this->assign("piclist","http://shop.zhuyeqing-tea.com/".trim($array1[0]));
		$this->assign("vo",$vo[0]);
		$this->display();
	}
	
	function introduce(){
		$this->display();
	}
	
	function introduce_tea($type=''){
		$this->display($type);
	}
	
	function tea($type=''){
		$this->display($type);
	}
	
	function lists($typeid='100',$p=''){
		$url=C('HTTP_API')."/kxapi/product_api.class.php?fun_name=Product&tid=".$typeid."&p=".$p;
		$list = json_decode(file_get_contents($url),TRUE);
		foreach($list as $key => $val){
			$array1=explode("|",$val['pic']);
			if(is_array($array1)){
				if((strpos($array1[0],"http://")===false) || (strpos($array1[0],"https://")===false)){
					$pic="http://shop.zhuyeqing-tea.com/".$array1[0];
				}else{
					$pic=$array1[0];
				}
			}
			$connet.='<li><a href="'.U('show',array('id'=>$val['id'])).'"><div class="img" onerror="this.src=\'/Public/static/images/nopic.jpg\'"><img src="'.$pic.'"/></div><h2>'.$val['title'].'</h2><p class="onlyheight">价格:'.$val['price'].'</p></a></li>';
		}
        $data['content'] = $connet;
		$this->ajaxReturn($data);
	}
	
	function listapi(){
		$myp=$_POST['myp'];
		$From=D("Shop");
		$limit=$myp*10;
		$list=$From->field("name,tel,address,picurl,id,lat,lng")->limit($limit,10)->select();
		//$list=$From->field("name")->limit(10)->select();
		$mycount=count($list);
		if($mycount==10){
			$data['mycount']  = $myp+1;
		}else{
			$data['mycount']  = 0;
		}
		$data['sql']  = $data['mycount'].$From->getlastsql();
		$data['status']  = 1;
        $data['content'] = $list;
		$this->ajaxReturn($data);
		//echo $data;
	}
	
	function inquiry(){
		$this->display();
	}
}