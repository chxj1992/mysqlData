<?php
    
require_once 'myTestCase.php';

/** 
 * 用户登录
 */
function login() {
     
    $mycase = new myTestCase();

    $user = $mycase->getData('user');
     
    //先登出 
    //$mycase->request( array('url'=>'/User/logout/') );  
    logout();
    $data = array(
           'url' =>  '/User/login/',
           'type' => 'POST',
           'data' => $user,
       ); 
    $res = $mycase->request($data);

    $mycase->assertTrue( $res->data != 0 );
    
} //End Of Func userLogin


/** 
 * 用户登出
 */
function logout() {
     
    $mycase = new myTestCase();
    $mycase->request( array('url'=>'/User/logout/') );  

} //End Of Func userLogin
