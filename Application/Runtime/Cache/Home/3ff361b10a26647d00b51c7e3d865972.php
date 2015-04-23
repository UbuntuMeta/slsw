<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no" name="viewport">
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
<title>个人注册</title>
<link type="text/css" rel="stylesheet" href="/dswx/Public/Home/css/css1.css">
<script src="/dswx/Public/Home/default/jquery-1.10.2.min.js"></script>
<script src="/dswx/Public/static/laycode/layer.min.js" type="text/javascript"></script>

</head>
<body>

<div class="top" >
  <img src="/dswx/Public/Home/image/0_04.jpg" class="logo"><b>  手机快速注册</b>
</div>
<div class="clear"></div>
<font color="red" size="2px">注意：所有项均为必填项</font>
<div class="box">
  <div class="enterprise">
    <div class="ent_title"><img src="/dswx/Public/Home/image/7_03.jpg" ><b>个人注册</b></div>    
    <div class="clear"></div>
    <ul>
	  <li class="ys_a"><b>用 户 名：</b><input type="text" name="user_username" id="user_username" class="ent_1" placeholder="请输入用户名" /></input> </li>
      <li class="ys_a"><b>用户密码：</b><input name="user_pass" type="password" class="ent_1" id="user_pass" placeholder="设置密码 " /></input></li>
      <li class="ys_a"><b>确认密码：</b><input name="user_pass1" type="password" class="ent_1" id="user_pass1" placeholder="再次输入密码确认 "/></input></li>
	  <li class="ys_a"><b>真实姓名：</b><input type="text" name="user_name" id="user_name" class="ent_1" placeholder="请输入姓名" /></input> </li>
      <li class="ys_a"><b>身份证号：</b><input name="user_sfz" type="text" class="ent_1" id="user_sfz" placeholder="居民身份证号码 " /></input></li>
      <li class="ys_a"><b>手机号码：</b><input name="user_phone" type="text" class="ent_1" id="user_phone" placeholder="请输入手机号码 " /></input></li>
        <li class="ys_a"><b>电子邮箱：</b><input name="user_email" type="text" class="ent_1" id="user_email" placeholder="请输入你的邮箱 " /></input></li>
        <!--user_email-->
<input name="wecha_id" type="hidden" id="wecha_id" value="<?php echo ($wecha_id); ?>"/>
	  
    </ul>
    <button  type="button" class="nextpage" id="submit-">注册</button> 
    <a href="javascript:history.go(-1)"><button type="button" class="previous">返回</button> </a>
  </div>
</div>
</body>
</html>
<script type="text/javascript">
 
$("#submit-").on("click",function(){
	var user_username = $("#user_username").val();
	var user_phone = $("#user_phone").val();
	var user_pass = $("#user_pass").val();
	var user_pass1 = $("#user_pass1").val();
	var user_sfz = $("#user_sfz").val();
	var taxclass = $("#tax_class").val();
	var user_name = $("#user_name").val();
    var user_email = $("#user_email").val();
    var flag = true;
	
	 if(user_username == ''){
		layer.msg("用户名没填写哦！", 2, 3);
		flag = false;
	}else if(user_phone == ''){
		layer.msg("手机没填写哦！", 2, 3);
		flag = false;
	}else if(!(/^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|18[0-9]{9}$/.test(user_phone))){
		layer.msg("请输入正确的手机号哦！", 2, 3);
		flag = false;
	}else if(user_pass == ''){
		layer.msg("密码没填写哦！", 2, 3);
		flag = false;
	}else if(user_pass1 == ''){
		layer.msg("确定密码没填写哦！", 2, 3);
		flag = false;
	}else if(user_sfz == ''){
		layer.msg("身份证没填写哦！", 2, 3);
		flag = false;
	}else if(user_name == ''){
		layer.msg("用户名还没有选择哦！", 2, 3);
		flag = false;
	}else if(user_pass != user_pass1){
		layer.msg("密码和确定密码不相同！", 2, 3);
		flag = false;
	}else if(user_username.length<6){
		layer.msg("用户名长度不能小于六位数！", 2, 3);
		flag = false;
	}else if(user_pass.length<6){
		layer.msg("密码长度不能小于六位数！", 2, 3);
		flag = false;
	}else if(!(/^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/.test(user_sfz)) ){
		layer.msg("身份证号码是15位或者18位！", 2, 3);
		flag = false;
	}else if(!(/^[a-zA-Z0-9]+@[a-zA-z0-9]+\.[a-zA-z0-9]+$/).test(user_email)){
		layer.msg("电子邮箱格式不正确！", 2, 3);
		flag = false;
	}
	
	if(flag){
	
	$.post("<?php echo U('register');?>",{'wecha_id':$("#wecha_id").val(),'user_username':$("#user_username").val(),'user_phone':$("#user_phone").val(),'user_pass':$("#user_pass").val(),'user_sfz':$("#user_sfz").val(),'taxclass':taxclass,'user_name':user_name,'user_email':user_email},function(data){
		if(data.status==1){
			layer.msg("注册成功", 2, 1);
			setTimeout(function (){window.location=data.url;},1000);
		}else{
			layer.msg(data.content, 2, 3);
			setTimeout(function (){$("#submit-").val("注册");},1000);
		}
   },'json');
   }
   });

</script>