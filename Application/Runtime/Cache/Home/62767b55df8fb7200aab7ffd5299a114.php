<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no" name="viewport">
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
<title>用户登录</title>
<link type="text/css" rel="stylesheet" href="/dswx/Public/Home/css/css2.css">
<script src="/dswx/Public/Home/default/jquery-1.10.2.min.js"></script>
<script src="/dswx/Public/static/laycode/layer.min.js" type="text/javascript"></script>

</head>
<body>

<div class="top" >
  <img src="/dswx/Public/Home/image/0_04.jpg" class="logo"><b>用户登录</b>
</div>
<div class="clear"></div>

<div class="box">
  <div class="enterprise">
	
    <ul>
      <li><input type="text" class="ent_1" name="user_username" id="user_username" placeholder="用户名 " /></input> </li>
      <li><input name="user_pass" type="password" class="ent_1" id="user_pass" placeholder="密码 " /></input><a  href="javascript:;" id="findBack" onclick="showFindEmail();">找回密码</a></li></li>
        <li> <a href="<?php echo U('Login/first');?>&new_reg=1">新用户注册</a></li>
      </li>
    </ul>   
    <button type="button" class="nextpage" id="submit-">登录</button>
  </div>

    <div class="hide" id="batch_clipWin" style="background:#ffffff;z-index:999;position:fixed;width:260px;height:120px; border:1px solid #ccc; border-radius: 3px; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);"">
    <div style="margin:15px 0px 0px 220px;"><a href="javascript:close_cur_win();">关闭</a></div>
    <div>
        <div style="margin: 0 0 0 40px;">
            &nbsp;&nbsp;请输入注册的电子邮箱:<br/>
            &nbsp;&nbsp;<input type="text" value="" id="user_email" name="user_email" />
            <br/> <input onclick="checkToFind();" type="button" class="typeButton" value="找回密码"/><input class="typeButton" type="button" value="关闭" onclick="close_cur_win();"/>
        </div>
    </div>
</div>
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



function showFindEmail() {
    //获取页面文档的高度
    var docheight = $(document).height(),docwidth =  $(document).width();
    var X = docwidth/2 - 270,
            Y = docheight/2 -110;
    Y = 300;
    $('#batch_clipWin').show().removeClass('hide');
    var width = $('#batch_clipWin').width(), height =  $('#batch_clipWin').height();
    $('#batch_clipWin').hide();
    $('#batch_clipWin').css({
        top:"50%",
        left:"50%",
        marginLeft:-1*width/2,
        marginTop:-1*height/2
    }).show();
    //追加一个层，使背景变灰
    $("body").append("<div id='greybackground'></div>");
    $("#greybackground").css({ "opacity": "0.5", "height": docheight ,"width": docwidth ,"position":"absolute", "top": "0px",  "right":"0px", "background-color": "black"});

}
function close_cur_win()
{
    $("#batch_clipWin").hide();
    $("#product_id_clip").text('');
    $("#greybackground").remove();
}

function checkToFind() {
    var flag =true;
    var user_email = $('#user_email').val();
    if (user_email == '') {
        alert('邮箱不能为空!');return false;
    }
    else if(!(/^[a-zA-Z0-9]+@[a-zA-z0-9]+\.[a-zA-z0-9]+$/).test(user_email)){
        layer.msg("电子邮箱格式不正确！", 2, 3);
        flag = false;
    }
    if (flag) {
        $.post("<?php echo U('Member/findBack');?>", {user_email: user_email}, function (data) {
            if (data == 'success') {
                alert('临时密码已经发送到你的电子邮箱,请查证.');
                close_cur_win();
            } else {
                alert(data);
                close_cur_win();
            }
        });
    }
}

</script>