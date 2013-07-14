<?php

$conf = array(
        
	//数据库信息
    'DB_HOST'                    =>     'localhost',
    'DB_USER'                    =>     'root',
    'DB_PWD'                     =>     'ukf8rz5gv8',
    'DB_NAME'                    =>     'mysqldata',
    'DB_PORT'                    =>     '',
    
	//'配置项'=>'配置值'
	'TOKEN_ON'					=> false,
	'URL_CASE_INSENSITIVE'       => true,
	'URL_MODEL'                 => 2,
    
    'DB_PREFIX'                  => 'cart_',

	//调试相关设置
	'SHOW_PAGE_TRACE'			 =>		 TRUE,
	'TRACE_CHUNK_SPILK'			 => 	 TRUE,
	'TRACE_LOG_SPILK'			 =>		 TRUE,

    'APP_STATUS'                 =>      'debug', 

    //2.2前向兼容
    'APP_DEBUG'					 =>      TRUE,
    'TMPL_TRACE_FILE'			 =>      TMPL_PATH.'default/Debug/page_trace.tpl',
    
    'TMPL_PARSE_STRING'  =>array(
        '__JS__' => '/Public/Js',
        '__CSS__' => '/Public/Css',
        '__BS__' => '/Public/Css/bootstrap',
        '__FLAT__' => '/Public/Css/flat-ui'
    ),

);

$model_name = require 'model_name.php';
$field_name = require 'field_name.php';
$functions= require 'functions.php';
$charts = require 'chart_type.php';
$user = require 'user.php';
$save_db = require 'save_db.php';

$conf = array_merge($conf,$model_name,$field_name,$functions,$charts,$user,$save_db);

return $conf;

?>
