<?php
 
    //定义项目名
    define('APP_NAME','Home');
    
    //定义项目路径
    define('APP_PATH','../Home/');
    
    //碎片路径
    define('RUNTIME_PATH','/tmp/fruitData/');
    
    //日志路径
    define('LOG_PATH','/var/log/fruitData/Home');
    
    //Webroot
    define('WEB_ROOT',dirname(__FILE__));

    //调试模式
    define('APP_DEBUG',TRUE);
   
    //加载内核文件
    require '../Kernel/ThinkPHP/ThinkPHP.php';
     
