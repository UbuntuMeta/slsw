<?php
namespace Sys\Controller;
use Think\Controller;
class SysadminController extends Controller {
		protected function _initialize(){
		if(!$_SESSION[C('SYS_AUTH_ID')]) {
			redirect(U('Sys/Public/login'));
		}
    }
}
?>