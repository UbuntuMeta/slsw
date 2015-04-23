<?php
return array(
	
	'MODULE_ALLOW_LIST'    =>    array('Home','Sys','Wap'),
    'DEFAULT_MODULE'       =>    'Home',  // 默认模块
    'URL_HTML_SUFFIX'=>'.html',
    'HTML_FILE_SUFFIX'=>'.html',
	'HTTP_URL'=>'http://',
	'HTTP_API'=>'http://',
	//'SHOW_PAGE_TRACE'=>1,//显示调试信息

//本地配置
	'DB_TYPE'=> 'mysql',
	'DB_HOST'=> 'localhost',
	'DB_NAME'=>'dsjwx',
	'DB_USER'=>'root',
	'DB_PWD'=>'12345',
	'DB_PORT'=>'3306',
	'DB_PREFIX'=>'t_',

//远程配置
//	'DB_TYPE'=> 'mssql',
//	'DB_HOST'=> '218.6.217.137',
//	'DB_NAME'=>'isszmv7',
//	'DB_USER'=>'sa',
//	'DB_PWD'=>'Lscity2236',
//	'DB_PORT'=>'9000',
//	'DB_PREFIX'=>'t_',

	'TMPL_PARSE_STRING'=>array(
			'__STATIC__'=>__ROOT__.'/Public/static',
			'__IMG__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/images',
			'__CSS__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/style',
			'__JS__'     => __ROOT__ . '/Public/' . MODULE_NAME . '/js',
			'__HOMEIMG__'   => __ROOT__ . '/Public/Home/image',
			'__HOMECSS__'=> __ROOT__.'/Public/Home/css',
			'__JQ__'     => __ROOT__ . '/Public/Home/xjs',
			'__SYSIMG__' => __ROOT__.'/Public/Sys/image',
			
	),
	//'SHOW_PAGE_TRACE' => true,
);