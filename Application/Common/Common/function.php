<?php
function CheckEmail($str){
	return eregi("^[a-z'0-9]+([._-][a-z'0-9]+)*@([a-z0-9]+([._-][a-z0-9]+))+$",$str);
}

function CheckPhones($str){
	if(preg_match("/^[0]?[1][3|5|8][0-9]{9}$/",$str)){
		if(strlen($str)==11)
		   return true;
	}else{
			 return false;
	}
}
// 检测输入的验证码是否正确，$code为用户输入的验证码字符串
function check_verify($code, $id = ''){
    $verify = new \Think\Verify();
    return $verify->check($code, $id);
}

function gbk_to_utf($str){
	return iconv('gb2312', 'utf-8',$str);
}

function Get_Art($id){
	if(empty($id)){
		return "未知";
	}
	$From=D("Weixin_menu");
	$vo=$From->field("title")->find($id);
	if($vo['title']){
		return $vo['title'];
	}else{
		return "未知";
	}
}

function Get_Product_type($id){
	if(empty($id)){
		return "未知";
	}
	$From=D("Product_type");
	$vo=$From->field("name")->find($id);
	if($vo['name']){
		return $vo['name'];
	}else{
		return "未知";
	}
}

function time_ymd($time){
	if(empty($time))
	   return "";
	else
	   return date('Y-m-d',strtotime($time));
}

function Get_Distance($lat1, $lng1, $lat2, $lng2, $len_type = 2, $decimal = 2){
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
	   return round($s,$decimal);
}

/**
 +----------------------------------------------------------
 * 对查询结果集进行排序
 +----------------------------------------------------------
 * @access public
 +----------------------------------------------------------
 * @param array $list 查询结果
 * @param string $field 排序的字段名
 * @param string $sortby 排序类型
 * asc正向排序 desc逆向排序 nat自然排序
 +----------------------------------------------------------
 * @return array
 +----------------------------------------------------------
 */
 function list_sort_by($list,$field, $sortby='asc') {
   if(is_array($list)){
       $refer = $resultSet = array();
       foreach ($list as $i => $data)
           $refer[$i] = &$data[$field];
       switch ($sortby) {
           case 'asc': // 正向排序
                asort($refer);
                break;
           case 'desc':// 逆向排序
                arsort($refer);
                break;
           case 'nat': // 自然排序
                natcasesort($refer);
                break;
       }
       foreach ( $refer as $key=> $val)
           $resultSet[] = &$list[$key];
       return $resultSet;
   }
   return false;
 }