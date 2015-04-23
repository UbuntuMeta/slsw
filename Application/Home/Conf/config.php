<?php
return array(
	'URL_MODEL'=>0,
	//模板
	//'TMPL_FILE_DEPR'=>'_',
	'DEFAULT_THEME'=>'default',
	'MEMBER_AUTH_ID'=>'member_id',
	'USER_TOKEN_ID'=>'user_token_id',
	/* 模板相关配置 */
    'TMPL_PARSE_STRING' => array(
        '__WAP__'     => __ROOT__ . '/Public/' . MODULE_NAME."/default",
		'__STYLE__'     => __ROOT__ . '/Public/' . MODULE_NAME,
		'__STATIC__'     => __ROOT__ . '/Public/static',
		'__HOMEIMG__'   => __ROOT__ . '/Public/Home/image',
		'__HOMECSS__'=> __ROOT__.'/Public/Home/css',
		'__JQ__'     => __ROOT__ . '/Public/Home/xjs',
		'__SYSIMG__' => __ROOT__.'/Public/Sys/image',

    ),
	'URL_MODEL'=>0,
	'URL_ROUTER_ON'   		=> true, 			//开启路由
	'URL_HTML_SUFFIX'		=>'shtml',			//伪静态后缀
	'URL_ROUTE_RULES' 		=> array( 			//定义路由规则
		'api/:token'        => 'Home/Weixin/index',
		
	),
);