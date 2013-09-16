<?php

$conf = array(
        
	//数据库信息
<<<<<<< HEAD
    'DB_HOST'                    =>     '202.115.22.218',
    'DB_USER'                    =>     'fruitv2online',
    'DB_PWD'                     =>     'fruitv2@#$larry',
    'DB_NAME'                    =>     'fruitv2',
    'DB_PORT'                    =>     '14747',

    //SQL数据库配置
    'DB_CONFIG1' => array(
        'db_type'  => 'mysqli',
        'db_user'  => 'root',
        'db_pwd'   => 'ukf8rz5gv8',
        'db_host'  => 'localhost',
        'db_port'  => '14747',
        'db_name'  => 'fruitdata_sql',
    ),    
=======
    'DB_HOST'                    =>     'localhost',
    'DB_USER'                    =>     'root',
    'DB_PWD'                     =>     'ukf8rz5gv8',
    'DB_NAME'                    =>     'mysqldata',
    'DB_PORT'                    =>     '',
>>>>>>> d21d6a0396c3a43f86b007586f2bd5aeb9e03eef
    
	//'配置项'=>'配置值'
	'TOKEN_ON'					=> false,
	'URL_CASE_INSENSITIVE'       => true,
	'URL_MODEL'                 => 2,
    
	//'DB_SQL_BUILD_CACHE' 		 =>     TRUE,
	//'DB_FIELDS_CACHE'			 =>     TRUE,
    'DB_PREFIX'                  => 'cart_',
    //'DB_PREFIX'                  => FALSE,
	//'DATA_CACHE_TYPE' 			 =>   	'file',
	//'DATA_CACHE_PATH'			 =>		'/tmp/delivery/Temp/',

    //模板主题名称
	//'DEFAULT_THEME'              =>     'default',
    //'TMPL_CACHE_ON'              =>     TRUE,
	
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
$conf = array_merge($conf,$model_name,$field_name,$functions,$charts,$user);

return $conf;

?>
