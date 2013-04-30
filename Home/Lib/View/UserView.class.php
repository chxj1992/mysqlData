<?php

if( !class_exists('CommonView') ) {
    require 'CommonView.class.php';
}

/** 
 * UserView extends CommonView 用户视图模型
 * @date   2013-04-17 
 */
class UserView extends CommonView {

    //主表
    public $viewFields = array(
        'User'=>array(
                'uname','email','point','regip','unread','isdel'=>'user_isdel',
            '_fields' => array('FROM_UNIXTIME(User.lasttime) AS user_lasttime',
                'FROM_UNIXTIME(User.regtime) AS user_regtime'),
            ),
    );  


    //关联表
    public $_relation = array();
 

} //End Of Class
