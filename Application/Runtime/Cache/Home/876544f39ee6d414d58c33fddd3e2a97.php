<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no" name="viewport">
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
<title>会员登录</title>
<link type="text/css" rel="stylesheet" href="/dswx/Public/Home/css/css2.css">
<script src="/dswx/Public/Home/default/jquery-1.10.2.min.js"></script>
<script src="/dswx/Public/static/laycode/layer.min.js" type="text/javascript"></script>

</head>
<body>

<div class="top" >
  <img src="/dswx/Public/Home/image/0_04.jpg" class="logo"><b>会员登录</b>
</div>
<div class="clear"></div>

<div class="box">
  <div class="enterprise">
	<?php echo ($msg); ?>
    <ul>
      <li><input type="text" class="ent_1" name="user_username" id="user_username" placeholder="用户名 " /></input> </li>
      <li><input name="user_pass" type="password" class="ent_1" id="user_pass" placeholder="密码 " /></input> <a  href="<?php echo U(RetrievePassword);?>" style="float: right;">找回密码</a><a href="<?php echo U('Login/first');?>&new_reg=1">新用户注册</a></li>
        </li>
    </ul>
    <button type="button" class="nextpage" id="submit-">登录</button>  
  </div>


</div>





</body>
</html>
<script type="text/javascript">
$("#submit-").on("click",function(){
	var user_username = $("#user_username").val();
	var user_pass = $("#user_pass").val();
	var flag = true;
	 if(user_username == ''){
		layer.msg("用户名没填写哦！", 2, 3);
		flag = false;
	}else if(user_pass == ''){
		layer.msg("密码没填写哦！", 2, 3);
		flag = false;
	}
	else if(user_username.length<6){
		layer.msg("用户名长度大于6！", 2, 3);
		flag = false;
	}else if(user_pass.length<6){
		layer.msg("密码长度大于6！", 2, 3);
		flag = false;
	}
	if(flag){
	
	$.post("<?php echo U('logindo');?>",{'user_username':$("#user_username").val(),'user_pass':$("#user_pass").val()},function(data){
		if(data.status==1){
			layer.msg(data.content, 2, 1);
			setTimeout(function (){window.location=data.url;},1000);
		}else{
			layer.msg(data.content, 2, 3);
			setTimeout(function (){$("#submit-").val("登录");},1000);
		}
   },'json');
   }
   });



</script>