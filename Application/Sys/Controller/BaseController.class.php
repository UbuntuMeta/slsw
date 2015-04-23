<?php
namespace Sys\Controller;
use Think\Controller;
class BaseController extends SysadminController {
    public function index(){
		$this->display();
    }
}