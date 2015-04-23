<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class LbsController extends Controller {
    public function index(){
		$this->display();
    }
	
	function show(){
		
		$this->display();
	}
	function show1($token='',$wecha_id=''){
		if(empty($id)){
			$this->show("非法操作哦！");
		}
		
		if($pots){
			$str=explode("_",$pots);
			$lat=$str[0];$lng=$str[1];
			$ssss=self::GetDistance($lat,$lng,$vo['lat'],$vo['lng']);
			$this->assign("ssss",$ssss);
		}
		$this->display();
	}
	function GetDistance($lat1, $lng1, $lat2, $lng2, $len_type = 2, $decimal = 2){
       $radLat1 = $lat1 * pi()/ 180.0;   //PI()圆周率
       $radLat2 = $lat2 * pi() / 180.0;
       $a = $radLat1 - $radLat2;
       $b = ($lng1 * pi() / 180.0) - ($lng2 * pi() / 180.0);
       $s = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2)));
       $s = $s * 6378.137;
       $s = round($s * 1000);
       if ($len_type > 1)
       {
           $s /= 1000;
       }
	   return "距离：大约".round($s,$decimal)."Km";
   }
}