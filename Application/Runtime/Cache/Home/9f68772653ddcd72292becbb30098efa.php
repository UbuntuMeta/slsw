<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no" name="viewport">
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
<title>预约管理</title>
<link type="text/css" rel="stylesheet" href="/dswx/Public/Home/css/css1.css">
<script src="/dswx/Public/Home/default/jquery-1.10.2.min.js"></script>
<script src="/Public/static/laycode/layer.min.js" type="text/javascript"></script>

</head>
<body>

<div class="top" >
  <img src="/dswx/Public/Home/image/0_04.jpg" class="logo"><b> 预约管理</b>
</div>
<div class="clear"></div>

<div class="make"><img src="/dswx/Public/Home/image/3_04.jpg" ><b>预约管理</b>  </div>
<div class="clear"></div>

<div class="box">
  <?php if(is_array($volist)): $i = 0; $__LIST__ = $volist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><ul>
  <li>预约类型名：<?php echo ($vo["business_name"]); ?></li>
  <li>预约号：<?php echo ($vo["order_number"]); ?></li>
  <li>预约时间：<?php echo ($vo["order_date"]); ?></li>
  <li>预约状态：<?php echo ($vo["status_name"]); ?>&nbsp;&nbsp;<?php echo ($vo["url"]); ?>
</li>
<br><hr><br>
    
  <!-- <li>
  <button type="submit" class="previous">取消预约</button> </a>
  </li> -->
  </ul><?php endforeach; endif; else: echo "" ;endif; ?>
  <ul>
  <li>
     <a href="<?php echo ($purl); ?>"><button type="button" class="previous">返回用户中心</button> </a>
  </li>
  </ul>
  
</div>





</body>
</html>