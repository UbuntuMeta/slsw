<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no" name="viewport">
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
<title>个人信息修改</title>
<link type="text/css" rel="stylesheet" href="/dswx/Public/Home/css/css2.css">
<script src="/dswx/Public/Home/default/jquery-1.10.2.min.js"></script>
<script src="/Public/static/laycode/layer.min.js" type="text/javascript"></script>

</head>
<body>

<div class="top" >
  <img src="/dswx/Public/Home/image/0_04.jpg" class="logo"><b>信息中心</b>
</div>
<div class="clear"></div>

<div class="box">
  <div class="enterprise">
    <div class="ent_title"><img src="/dswx/Public/Home/image/7_03.jpg" ><b>个人信息</b></div>    
    <div class="clear"></div>
 
   <ul class="informat">
	 <li><b>欢迎您,</b> <?php echo ($user["user_username"]); ?></li>
      <li><b>姓名：</b> <input type="text" name="user_name" id="user_name" value="<?php echo ($user["user_name"]); ?>" class="yangs"/></li>
      <li><b>身份证号码：</b> <input type="text" name="user_sfz" id="user_sfz" value="<?php echo ($user["user_sfz"]); ?>" class="yangs"/></li>
      <li><b>手机号码：</b><input type="text" name="user_phone" id="user_phone" value="<?php echo ($user["user_phone"]); ?>" class="yangs"/> </li>
 	</ul>
  </div>
  <div class="jg">
  <button  type="button" class="jbang" id="submit-">保存</button> 
   <a href="javascript:history.go(-1)"><button type="button" class="jbang_b" name="bangding" id="submit1">取消</button></a>
  </div>

</div>





</body>
</html>
<script type="text/javascript">
$("#submit-").on("click",function(){
	var user_phone = $("#user_phone").val();
	var user_sfz = $("#user_sfz").val();
	var taxclass = $("#tax_class").val();
	var user_name = $("#user_name").val();
	var flag = true;
	if(user_phone == ''){
		layer.msg("手机没填写哦！", 2, 3);
		flag = false;
	}else if(!(/^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|18[0-9]{9}$/.test(user_phone))){
		layer.msg("请输入正确的手机号哦！", 2, 3);
		flag = false;
	}else if(user_sfz == ''){
		layer.msg("身份证没填写哦！", 2, 3);
		flag = false;
	}else if(user_name == ''){
		layer.msg("用户名还没有选择哦！", 2, 3);
		flag = false;
	}else if(!(/^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/.test(user_sfz)) ){
		layer.msg("身份证号码是15位或者18位！", 2, 3);
		flag = false;
	}
	if(flag){
	
	$.post("<?php echo U('updateuserdo');?>",{'user_name':$("#user_name").val(),'user_phone':$("#user_phone").val(),'user_sfz':$("#user_sfz").val(),'taxclass':taxclass},function(data){
		if(data.status==1){
			
			layer.msg(data.content, 2, 1);
			
			setTimeout(function (){window.location=data.url;},1000);
		}else{
			layer.msg(data.content, 2, 3);
			setTimeout(function (){$("#submit-").val("修改");},1000);
		}
   },'json');
   }
   });
   
   
   
   
   </script>