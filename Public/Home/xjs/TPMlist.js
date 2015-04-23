//元素属性： data-api，请求api地址 ； data-datas 请求参数 data-tpl 模板地址 data-tabletpagesize 平板每页显示条数, data-phonepagesize 手机每页显示条数
;(function($){
$.fn.extend({
 'TPMlist':function(options){
     var defaults={
        "param_pagesize":"pagesize",
        "param_page":"page",
        "tabletpagesize":20,
        "phonepagesize":10
     };
    options=$.extend(defaults,options);
    $(this).each(function(){
       //获得api
       var api=$(this).data('api');
        //获得请求参数
        var datas=$(this).data('datas');
        //获得模板
        var tpl=$(this).data('tpl');
		
		var currentpage = 1;
        //获得数据集合名称
       //获得pagesize
		var type=$(window).height()>767?'tablet':'phone';
		var defaultpagesize='tablet'==type?options.tabletpagesize:options.phonepagesize;//默认每页显示条数
		var pagesize=$(this).data(type+'pagesize') || defaultpagesize;
		$children=$('#thelist');
		//加载控件
		var sc=$(this).TPMpulltorefresh(function(page){
			if (page == 1)
			{
				currentpage = 1;
			}else{
				currentpage=$children.attr('page');
			}
			//获取最新请求参数
			datas = $children.attr('data-datas');
			$children.TPMgetListData(api,datas,tpl,pagesize,currentpage,this,options);
       });
       $children.TPMgetListData(api,datas,tpl,pagesize,currentpage,sc,options);
       
    });
 },
'TPMgetListData':function(api,datas,tpl,pagesize,page,sc,options){
	var params=datas?datas.split('&'):{};
	var datas_obj={};
	for(var i=0;i<params.length;i++){
		var p=params[i].split('=');
		datas_obj[p[0]]=p[1];
	}
	datas_obj[options.param_pagesize]=pagesize;
	datas_obj[options.param_page]=page;
	var $this=$(this);
	//请求api
	TPM.sendAjax(api,datas_obj,'get',function(response){
		//渲染模板
		$.get(tpl,function(d,x,s){
			var html=TPM.parseTpl(d,response);
			//判断是否为第一页，如果为第一页，清空以前数据然后重新加载，如果不是第一页数据进行累加
			if(1==page){
				$this.empty(); 
			}
			if (html.trim() == '')
			{
				clickautohide(0,1,'没有更多信息啦');
			}else{
				$this.append(html);
			}
			sc.refresh();//iscroll refresh;
			//记录当前页面
			$this.attr({'page':response.page});

		},'text');
	});
 },
//拉动刷新
'TPMpulltorefresh':function(cb){
	$(this).before('<div id="pullDown"><span class="pullDownIcon"></span><span class="pullDownLabel">下拉刷新...</span></div>');
	$(this).after('<div id="pullUp"><span class="pullUpIcon"></span><span class="pullUpLabel">上拉刷新...</span></div>');
	pullDownEl = document.getElementById('pullDown');
	pullDownOffset = pullDownEl.offsetHeight;
	pullUpEl = document.getElementById('pullUp');	
	pullUpOffset = pullUpEl.offsetHeight;
	
	myScroll = new iScroll('wrapper', {
		useTransition: true,
		topOffset: pullDownOffset,
		onRefresh: function () {
			if (pullDownEl.className.match('loading')) {
				pullDownEl.className = '';
				pullDownEl.querySelector('.pullDownLabel').innerHTML = '下拉可以刷新...';
			} else if (pullUpEl.className.match('loading')) {
				pullUpEl.className = '';
				pullUpEl.querySelector('.pullUpLabel').innerHTML = '上拉加载更多...';
			}
		},
		onScrollMove: function () {
			if (this.y > 5 && !pullDownEl.className.match('flip')) {
				pullDownEl.className = 'flip';
				pullDownEl.querySelector('.pullDownLabel').innerHTML = '松开可以刷新...';
				this.minScrollY = 0;
			} else if (this.y < 5 && pullDownEl.className.match('flip')) {
				pullDownEl.className = '';
				pullDownEl.querySelector('.pullDownLabel').innerHTML = '下拉可以刷新...';
				this.minScrollY = -pullDownOffset;
			} else if (this.y < (this.maxScrollY - 5) && !pullUpEl.className.match('flip')) {
				pullUpEl.className = 'flip';
				pullUpEl.querySelector('.pullUpLabel').innerHTML = '松开可以刷新...';
				this.maxScrollY = this.maxScrollY;
			} else if (this.y > (this.maxScrollY + 5) && pullUpEl.className.match('flip')) {
				pullUpEl.className = '';
				pullUpEl.querySelector('.pullUpLabel').innerHTML = '上拉加载更多...';
				this.maxScrollY = pullUpOffset;
			}
		},
		onScrollEnd: function () {
			if (pullDownEl.className.match('flip')) {
				pullDownEl.className = 'loading';
				pullDownEl.querySelector('.pullDownLabel').innerHTML = '加载中...';				
				cb.call(this,1);//触发回调函数
			} else if (pullUpEl.className.match('flip')) {
				pullUpEl.className = 'loading';
				pullUpEl.querySelector('.pullUpLabel').innerHTML = '加载中...';				
				cb.call(this);//触发回调函数
			}
		}
	});
    return myScroll;
 }

});
})(jQuery);

