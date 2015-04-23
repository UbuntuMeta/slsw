<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no" name="viewport">
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
<title>个人信息管理</title>
<link type="text/css" rel="stylesheet" href="/dswx/Public/Home/css/css2.css">
<script src="/dswx/Public/Home/default/jquery-1.10.2.min.js"></script>
<script src="/dswx/Public/static/laycode/layer.min.js" type="text/javascript"></script>

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
	 <li>欢迎您, <?php echo ($user["user_username"]); ?></li>
      <li><p>姓名：</p> <?php echo ($user["user_name"]); ?></li>
      <li><p>身份证号码：</p> <?php echo ($user["user_sfz"]); ?></li>
      <li><p>手机号码：</p><?php echo ($user["user_phone"]); ?> </li>
       <?php if($user['user_email']){?><li><p>电子邮箱：</p><?php echo $user['user_email'];?></li><?php }?>
 	</ul>
  </div>
  <div class="jg">
  <a href="/dswx/index.php/Home/Member/userupdate"><button  type="button" class="jbang" id="submit-">修改信息</button></a>
 <a href="<?php echo U('Login/loginoutshow');?>"> <button type="button" class="jbang_b" name="bangding" >注销</button></a>
  </div>
  <div class="jg">
  <a href="<?php echo ($pcurl); ?>"><button type="button" class="jbang">预约</button> </a>
  <a href="/dswx/index.php/Home/Member/orderlist?class_id=1"><button type="button" class="jbang_b">查看预约</button> </a>
  </div>
  <div class="jg">
 <button type="button" id="submit1" class="jbang">退出</button>

  
  </div>
</div>





</body>
</html>
<script type="text/javascript">

   
   $("#submit1").on("click",function(){
	
	$.post("<?php echo U('Login/loginout1');?>",function(data){
		if(data.status==1){
			
			layer.msg(data.content, 2, 1);
			setTimeout(function (){window.location=data.url;},1000);
		}else{
			layer.msg(data.content, 2, 3);
			setTimeout(function (){$("#submit-").val("修改");},1000);
		}
   },'json');
   }
   );
   
   
   </script>