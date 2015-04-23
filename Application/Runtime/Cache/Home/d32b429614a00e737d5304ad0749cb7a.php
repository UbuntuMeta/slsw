<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no" name="viewport">
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
<title>用户注销</title>
<link type="text/css" rel="stylesheet" href="/dswx/Public/Home/css/css1.css">
<script src="/dswx/Public/Home/default/jquery-1.10.2.min.js"></script>
<script src="/Public/static/laycode/layer.min.js" type="text/javascript"></script>

</head>
<body>

<div class="top" >
  <img src="/dswx/Public/Home/image/0_04.jpg" class="logo"><b> 用户注销</b>
</div>
<div class="clear"></div>

<div class="make"><img src="/dswx/Public/Home/image/3_04.jpg" ><b>用户注销</b>  </div>
<div class="clear"></div>

<div class="box">
  
  
  <ul>
  <li>您确定真的要注销吗？</li>
  <li>注销会删除您注册信息和预约信息，你需要重新注册</li>
 

   <li><button  type="button" class="nextpage" id="submit-">确定</button> </li>
    <li><a href="javascript:history.go(-1)"><button type="button" class="previous">取消</button> </a></li>


    
  <!-- <li>
  <button type="submit" class="previous">取消预约</button> </a>
  </li> -->
  </ul>

  
  </ul>
  
</div>





</body>
</html>
<script type="text/javascript">
 
$("#submit-").on("click",function(){
	$.post("<?php echo U('loginout');?>",function(data){
		if(data.status==1){
			layer.msg("注销成功", 2, 1);
			setTimeout(function (){window.location=data.url;},1000);
		}else{
			layer.msg(data.content, 2, 3);
			setTimeout(function (){$("#submit-").val("注册");},1000);
		}
   },'json');
   
   });

</script>