<?php
// 本类由系统自动生成，仅供测试用途
namespace Sys\Controller;
use Think\Controller;
class OperateController extends SysadminController {
	public function _initialize(){
		parent::_initialize();
		$this->mysql=M('Requestdata_in');
	
	}
    public function index(){
		$this->display();
    }
	
	function user(){
		$db=D("Rm_vip_info_wx");
		//$where['articletype']='news';
		$count=$db->where($where)->count();
		$Page = new \Think\Page($count, $size=15); //count总数 $size每页显示数
		$limit = $Page->firstRow . ',' . $Page->listRows;
		$list=$db->where($where)->limit($Page->firstRow. ',' . $Page->listRows)->order("id desc")->select();
		$this->pager_bar = $Page->show(); //显示分页导航
		$this->assign('list',$list);
		$this->assign('count',$count);
		$this->display();
	}
	
	function synchronization($id=''){
		$return['status']=0;
		
		//$url=C('HTTP_API')."/kxapi/api.php?fun_name=GetUserAllInfo&uid=".$id;
		$url="http://weixin.zhuyeqing-tea.com"."/kxapi/api.php?fun_name=GetUserAllInfo&uid=".$id;
		$rids = json_decode(file_get_contents($url),TRUE);
		$rid=$rids[0];
		if($rid){
			$date['card_id']=$rid['card_id'];
			$date['card_no']=$rid['card_no'];
			$date['card_type']=$rid['card_type'];
			$date['vip_name']=$rid['vip_name'];
			$date['vip_sex']=$rid['vip_sex'];
			$date['vip_add']=$rid['vip_add'];
			$date['vip_zip']=$rid['vip_zip'];
			$date['vip_tel']=$rid['vip_tel'];
			$date['vip_email']=$rid['vip_email'];
			$date['vip_tax']=$rid['vip_tax'];
			$date['card_status']=$rid['card_status'];
			$date['oper_id']=$rid['oper_id'];
			$date['oper_date']=$rid['oper_date'];
			$date['vip_start_date']=$rid['vip_start_date'];
			$date['vip_end_date']=$rid['vip_end_date'];
			$date['use_num']=$rid['use_num'];
			$date['birthday']=$rid['birthdays'];
			$date['social_id']=$rid['social_id'];
			$date['save_amt']=$rid['save_amt'];
			$date['residual_amt']=$rid['residual_amt'];
			$date['consum_amt']=$rid['consum_amt'];
			$date['consum_count']=$rid['consum_count'];
			$date['company']=$rid['company'];
			$date['duty']=$rid['duty'];
			$date['degree']=$rid['degree'];
			$date['mobile']=$rid['mobile'];
			$date['homeplace']=$rid['homeplace'];
			$date['reg_place']=$rid['reg_place'];
			$date['live_place']=$rid['live_place'];
			$date['marriage']=$rid['marriage'];
			$date['salary_level']=$rid['salary_level'];
			$date['nation']=$rid['nation'];
			$date['height']=$rid['height'];
			$date['weight']=$rid['weight'];
			$date['blood_type']=$rid['blood_type'];
			$date['favor']=$rid['favor'];
			$date['now_acc_num']=$rid['now_acc_num'];
			$date['acc_num']=$rid['acc_num'];
			$date['dec_num']=$rid['dec_num'];
			$date['pass']=$rid['pass'];
			$date['modify_date']=$rid['modify_date'];
			$date['com_flag']=$rid['com_flag'];
			$date['photo']=$rid['photo'];
			$date['memo']=$rid['memo'];
			$date['father_card']=$rid['father_card'];
			$date['item_amt']=$rid['item_amt'];
			$date['branch_no']=$rid['branch_no'];
			$date['source_card_id']=$rid['card_type'];
			$date['sav_start_date']=$rid['sav_start_date'];
			$date['sav_end_date']=$rid['sav_end_date'];
			$date['vip_add1']=$rid['vip_add1'];
			$date['vip_add2']=$rid['vip_add2'];
			$date['annex_property1']=$rid['annex_property1'];
			$date['annex_property2']=$rid['annex_property2'];
			$date['annex_property3']=$rid['annex_property3'];
			$date['annex_property4']=$rid['annex_property4'];
			$date['annex_property5']=$rid['annex_property5'];
			$date['annex_property6']=$rid['annex_property6'];
			$date['annex_property7']=$rid['annex_property7'];
			$date['annex_property8']=$rid['annex_property8'];
			$date['annex_property9']=$rid['annex_property9'];
			$date['annex_property10']=$rid['annex_property10'];
			$date['picture_type']=$rid['picture_type'];
			$date['vip_spec_date1']=$rid['vip_spec_date1'];
			$date['vip_spec_date2']=$rid['vip_spec_date2'];
			$date['vip_spec_date3']=$rid['vip_spec_date3'];
			$date['bth_flag']=$rid['bth_flag'];
			$date['vip_credentials']=$rid['vip_credentials'];
			$date['vip_work']=$rid['vip_work'];
			$date['vip_intent']=$rid['vip_intent'];
			$date['branch_no2']=$rid['branch_no2'];
			$date['register']=$rid['register'];
			//70
		}
		
		if($date){
			$From=D("Rm_vip_info_wx");
			$where['uid']=$id;
			$date['endtime']=date('Y-m-d H:i:s');
			//$date['endtime']='';
			$vo=$From->where($where)->save($date);
			if($vo){
				$return['status']=1;
			}
		}
		$this->ajaxReturn($return);
	}
	
	//Operate/interaction
	function interaction($year='',$month=''){
		
		if(empty($month)){//$this->_get('month')==false){
			$month=date('m');
		}else{
			$month=$month;
		}
		$thisYear=date('Y');
		if(empty($year)){
			$year=$thisYear;
		}else{
			$year=$year;
		}
		$this->assign('month',$month);
		$this->assign('year',$year);
		$lastyear=$thisYear-1;
		if ($year==$lastyear){
			$yearOption='<option value="'.$lastyear.'" selected>'.$lastyear.'</option><option value="'.$thisYear.'">'.$thisYear.'</option>';
		}else {
			$yearOption='<option value="'.$lastyear.'">'.$lastyear.'</option><option value="'.$thisYear.'" selected>'.$thisYear.'</option>';
		}
		$this->assign('yearOption',$yearOption);
		$where=array('month'=>$month,'year'=>$year);
		$list=$this->mysql->where($where)->limit(31)->order('id desc')->select();
		$this->assign('list',$list);
		$this->display();
	}
	
	function other($mydate='',$mykey=''){
		if(empty($mydate)){
			$mydate=date('Y-m-d');
		}
		if($mykey){
			$where['methods']=array("like","%".$mykey."%");
		}
		$str=explode("-",$mydate);
		$where['year']=$str[0];
		$where['month']=$str[1];
		$where['day']=$str[2];
		$this->assign('mydate',$mydate);
		$this->assign('mykey',$mykey);
		$db=D("Requestdata");
		//$where['articletype']='news';
		$count=$db->where($where)->count();
		$Page = new \Think\Page($count, $size=15); //count总数 $size每页显示数
		$limit = $Page->firstRow . ',' . $Page->listRows;
		$list=$db->where($where)->limit($Page->firstRow. ',' . $Page->listRows)->order("time desc")->select();
		$this->pager_bar = $Page->show(); //显示分页导航
		$this->assign('list',$list);
		$this->assign('count',$count);
		$this->display();
	}
}