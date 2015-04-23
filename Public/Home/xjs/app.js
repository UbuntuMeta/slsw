function login(){
	$('#subform').submit();
}
function bodyToLeft(){
	var status = $('.leftBox').attr('id');
	if (status == 'on')
	{
		$('.leftBox').attr({'id':''});
		$('.leftBox').animate({
			left: '-70%'
		},100);
		$('.Mob_head').animate({
			left: '0'
		},100);
		$('.Mob_foot').animate({
			left: '0'
		},100);
		$('.content').animate({
			left: '0'
		},100);
		$('.content').css({
			overflow :'auto',
			width: '100%'
		})
	}else{
		$('.leftBox').attr({'id':'on'});
		$('.leftBox').animate({
			left: '0'
		},100);
		$('.Mob_head').animate({
			left: '70%'
		},100);
		$('.Mob_foot').animate({
			left: '70%'
		},100);
		$('.content').animate({
			left: '70%'
		},100);
		$('.indexList').css({
			width: $('.content').width()-4
		})
		$('.content').css({
			overflow :'hidden',
			width: '30%'
		})

	}
}
function clickme(i,k){
	var tip = "";
	switch(i){
		case 1:
			tip = "服务器繁忙，请稍后再试。";
		break;
		case 4:
			tip = "设置成功！";
		break;
		case 5:
			tip = "数据拉取失败";
		break;
		case 6:
			tip = "正在加载中，请稍后...";
		break;case 7:
			tip = "登陆成功！";
		break;
	}
	ZENG.msgbox.show(tip, k);
}
function clickautohide(i,k,text){

	switch(i){
		case 1:
			tip = "服务器繁忙，请稍后再试。";
		break;
		case 4:
			tip = "设置成功！";
		break;
		case 5:
			tip = "数据拉取失败";
		break;
		case 6:
			tip = "正在加载中，请稍后...";
		break;
		case 7:
			tip = "登陆成功！";
		break;
		case 0:
			tip = text;
		break;
	}
	ZENG.msgbox.show(tip, k, 3000);
}

function inFace(){
	$('#FaceBox').show();
	$('#FaceBox h6 i').click(function(){
		$('#FaceBox').hide();
	})
}
function toComment(){
	var n = $('.ComInput').val();
	var id = $('.artid').val();
	var author_id  = $('.author_id').val();
	var author = $('.author').val();
	if (author != '' && author_id != '')
	{
		var pos = 'content='+n+'&record_id='+id+'&author='+author+'&author_id='+author_id;
	}else{
		var pos = 'content='+n+'&record_id='+id
	}
	$.post('/User/insertComment',pos,function(json){
		if (json.status == 1)
		{
			clickautohide(0,4,'评论成功！');
			UBBFace($('.artid').val(),1);
			$('.ComInput').val('');
			$('.author_id').val('');
			$('.author').val('');
		}else if(json.status == 0){
			clickautohide(0,5,json.info);
			setTimeout('bodyToLeft()',2000);
		}else{
			clickautohide(0,5,json.info);
		}
	})
}
function FaceIn(t){
	var n = $('.ComInput').val();
	$('.ComInput').val( n + t);
	$('#FaceBox').hide();
}
	
	/*筛选*/
function navTabList(n){
	$('.TabMore').show();
	$('.TabMore').find('p').eq(n).show().siblings().hide();
	$(this).parent().addClass('on').siblings().removeClass('on');
	$(this).html($(this).attr('attr')+'▲');
	$(this).parent().siblings().find('cite').each(function(){
		$(this).html($(this).attr('attr')+'▼');
	})
}
function relApp(author,author_id,nickname){
	$('.author').val(author);
	$('.author_id').val(author_id);
	$('.ComInput').attr({'placeholder':'回复：'+nickname});
	$('.ComInput').focus();
}
function Hashtable() {
    this._hash = new Object();
    this.put = function(key, value) {
        if (typeof (key) != "undefined") {
            if (this.containsKey(key) == false) {
                this._hash[key] = typeof (value) == "undefined" ? null : value;
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    this.remove = function(key) { delete this._hash[key]; }
    this.size = function() { var i = 0; for (var k in this._hash) { i++; } return i; }
    this.get = function(key) { return this._hash[key]; }
    this.containsKey = function(key) { return typeof (this._hash[key]) != "undefined"; }
    this.clear = function() { for (var k in this._hash) { delete this._hash[k]; } }
}

var emotions = new Array();
var categorys = new Array();// 分组
var uSinaEmotionsHt = new Hashtable();

function UBBmsg(){
	$.getJSON('/emotions.json',function(data){
		if (data)
		{
			for ( var i in data) {
				if (data[i].category == '') {
					data[i].category = '默认';
				}
				if (emotions[data[i].category] == undefined) {
					emotions[data[i].category] = new Array();
					categorys.push(data[i].category);
				}
				emotions[data[i].category].push( {
					name : data[i].phrase,
					icon : data[i].icon
				});
				uSinaEmotionsHt.put(data[i].phrase, data[i].icon);
			}
		}
	});
}

//替换
function AnalyticEmotion(s) {
	if(typeof (s) != "undefined") {
		if(s.indexOf('[') >0 && s.indexOf(']') >0){ 
			var sArr = s.match(/\[.*?\]/g);
			for(var i = 0; i < sArr.length; i++){
				if(uSinaEmotionsHt.containsKey(sArr[i])) {
					var reStr = "<img src=\"" + uSinaEmotionsHt.get(sArr[i]) + "\" height=\"22\" width=\"22\" />";
					s = s.replace(sArr[i], reStr);
				}
			}
		}
	}
	return s;
}

/*分享*/
function more(){
	$('.Mob_More').show();
}
function sinashare(){
	var url = 'http://service.weibo.com/share/share.php?url='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title)+'&appkey=1343713053&searchPic=true';
	window.open(url,'_blank');
}
function qqshare(){
	var p = {
		url:location.href,
		showcount:'1',/*是否显示分享总数,显示：'1'，不显示：'0' */
		desc:'内容真不错~大家快来看！~',/*默认分享理由(可选)*/
		summary:'',/*分享摘要(可选)*/
		title:document.title,/*分享标题(可选)*/
		site:'张垣生活网',/*分享来源 如：腾讯网(可选)*/
		pics:'', /*分享图片的路径(可选)*/
		style:'203',
		width:98,
		height:22
	};
	var s = [];
	var tu = '';
	for(var i in p){
		tu += i + '=' + encodeURIComponent(p[i]||'') + '&';
	}
	var url = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?'+ tu;
	window.open(url,'_blank');
}
function closeMore(){
	$('.Mob_More').hide();
}

function once(){
	$.post('/User/once','key=52zjk.cn',function(date){
		if (date == 0)
		{
			clickautohide(0,4,'签到成功！垣宝 + 5 积分 + 10！');
			flag();
			if(flag){
				TPM.reload(TPM.op.main);
			}else{
				location.reload();
			}
		}else{
			clickautohide(0,5,date);
		}
	})
}
function UBBFace(record_id,cpage){
	$.post('/Mob/comment','record_id='+record_id+'&cpage='+cpage,function(_data){
		if(_data && _data.status)
		  {
			 // 处理内容
			 var content = '';
			 var result  = _data.data.result;
			 for(var i=0 ; i<result.length; i++)
			 {
				  content+= AnalyticEmotion(result[i]);
			 }
			$('.countComment').html(_data.data.count);
			$('#comment_list').html(content);
			// 处理分页
			var page = '';
			var _page = _data.data.page || '';
			if (!cpage)
			{
				cp = 0;
			}else{
				cp = cpage -1;
			}
			if(_page)
			{
				for(var i =0 ; i<_page.length; i++)
				{
					if(i == cp)
					{
						var clas = 'class="current"';
					}else{
						var clas = 'onclick="$.read('+_data.data.page[i]+');"';
					}
					page+='<a href="javascript:void(0)" '+ clas +'>'+_data.data.page[i]+'</a>&nbsp;&nbsp;';
				}
			}
			 $(".page").html(page);
		  }
	},'json');
}


function BuildList(url,pos){
	TPM.sendAjax(url+'/Type/json',pos,'get',function(response){
		//渲染模板
		$.get(url+'/Type/TPl',function(d,x,s){
			var html=TPM.parseTpl(d,response);
			var page = $('#thelist').attr('page');
			if (html.trim() == '')
			{
				clickautohide(0,1,'没有更多信息啦');
				$('#thelist').html('');
			}else{
				$('#thelist').html(html);
				eg: setTimeout(function () { myScroll.refresh(); }, 0); 
			}
		},'text')
	});
}
function hrContent(){
	//投递简历
	$('.hrBottom .icon1').click(function(){
		var id = $(this).attr('attr');
		$.post('/User/tojob/','id='+id,function(data){
			if (data == 99)
			{
				TPM.alert('还没有登录！');
				bodyToLeft();
			}else if(data ==0){
				TPM.alert('投递成功！');
			}else{
				TPM.alert(data);
			}
		})
	});
	$('.hrBottom .icon2').click(function(){
		var id = $(this).attr('attr');
		$.post('/User/toad/','id='+id,function(data){
			if (data == 99)
			{
				TPM.alert('还没有登录！');
				bodyToLeft();
			}else if(data ==0){
				TPM.alert('收藏成功！');
			}else{
				TPM.alert(data);
			}
		});
	});
	$('.callGM').click(function(){
		var id = $(this).attr('attr');
		var where = 'aid='+id+'&msg=手机举报';
		$.post('/User/callsto/',where,function(data){
			if (data == 99)
			{
				TPM.alert('还没有登录！');
				bodyToLeft();
			}else if(data ==0){
				TPM.alert('您已经举报过该信息，请勿重复举报！');
			}else{
				TPM.alert(data);
			}
		})
	})
}
function freeEat(now){
	var re = /(^(13|14|15|18)[0-9]{9}$)|(^0{0,1}13[0-9]{9}$)/;
	$('#sex1').click(function(){
		$('#sex').val(2);
	})
	$('#sex2').click(function(){
		$('#sex').val(1);
	})
	$('.formsubmit').click(function(){
		if(now == '已结束'){
			TPM.alert('本次活动报名已经停止！');
			return false;
		}
		var name = $('#name').val();
		var tel = $('#tel').val();
		if (name == '')
		{
			TPM.alert('姓名不能为空');
			$("#name").focus();
			return false;
		}
		var tel = $("#tel").val();
		var name = $("#name").val();
		if(tel == ""){
			TPM.alert('手机号码不能为空！');
			$("#tel").focus();
			return false;
		}
		if(!re.test(tel)){
			TPM.alert('手机格式不正确!');
			$("#tel").focus();
			return false;
		}
		$.post('/User/insterAct',$('#EatFrom').serialize(),function(data){
			if (data == 0)
			{
				TPM.alert('报名成功！!');
			}else{
				TPM.alert(data);
			}
		});
	})
}
function toBeta(v){
	$.post('/Index/beta','v='+v,function(json){
		if (json.data == 1)
		{
			var r = confirm(json.info);
			if (r==true)
			{
				window.open(json.url);
			}
		}else{
			clickautohide(0,5,json.info);
		}
	},'json')
};
function LightBoxGo(){
	$(".main_visual").hover(function(){
		$("#btn_prev,#btn_next").fadeIn()
	},function(){
		$("#btn_prev,#btn_next").fadeOut()
	});

	$dragBln = false;

	$(".main_image").touchSlider({
		flexible : true,
		speed : 200,
		btn_prev : $("#btn_prev"),
		btn_next : $("#btn_next"),
		paging : $(".flicking_con a"),
		counter : function (e){
			$(".flicking_con a").removeClass("on").eq(e.current-1).addClass("on");
		}
	});
	$(".main_image").bind("mousedown", function() {
		$dragBln = false;
	});
	
	$(".main_image").bind("dragstart", function() {
		$dragBln = true;
	});
	
	$(".main_image a").click(function(){
		if($dragBln) {
			return false;
		}
	});

	/*timer = setInterval(function(){
	}, 5000);
	
	$(".main_visual").hover(function(){
		clearInterval(timer);
	},function(){
		timer = setInterval(function(){
			$("#btn_next").click();
		},5000);
	});
	
	$(".main_image").bind("touchstart",function(){
		clearInterval(timer);
	}).bind("touchend", function(){
		timer = setInterval(function(){
			$("#btn_next").click();
		}, 5000);
	});*/
}
function imgListBoxH(){
	$("#btn_next").click();
	var imgH = $('.main_image').find('img').eq(0).height();
	$('.main_image').height(imgH);
	$('.main_image').find('img').height(imgH);
}
function kindto(tag){
	$.getScript('http://m.52zjk.cn/client/Public/Js/kindeditor/kindeditor-min.js', function() {
		KindEditor.create('#'+tag, {
			themeType : 'qq',
			items : [
				'bold','italic','underline'
			]
		});
	});
}