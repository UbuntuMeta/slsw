<?php
return array(
	'DEFAULT_THEME'=>'default',
	'SYS_AUTH_ID'=>'sys_id',
	/* 模板相关配置 */
    /*'TMPL_PARSE_STRING' => array(
        '__STATIC__' => __ROOT__ . '/Public/static',
        //'__ADDONS__' => __ROOT__ . '/Public/' . MODULE_NAME . '/Addons',
        '__IMG__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/images',
        '__CSS__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/style',
        '__JS__'     => __ROOT__ . '/Public/' . MODULE_NAME . '/js',
    ),*/
	'TMPL_PARSE_STRING'=>array(
			'__STATIC__'=>__ROOT__.'/Public/static',
			'__IMG__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/images',
			'__CSS__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/style',
			'__JS__'     => __ROOT__ . '/Public/' . MODULE_NAME . '/js',
			'__JQ__'     => __ROOT__ . '/Public/Home/xjs',
			'__SYSIMG__' => __ROOT__.'/Public/Sys/image',
	
	),
);