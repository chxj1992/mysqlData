<?php

if( !class_exists('CommonView') ) {
    require 'CommonView.class.php';
}

/** 
 * UserAddressView extends CommonView 用户地址视图模型
 * @date   2013-04-17 
 */
class UserAddressView extends CommonView {

    //主表
    public $viewFields = array(
        'UserAddress'=>array( 
            'addressid','uid','receiver','address','link'
        ),
    );  
 
    //用户
    protected $User = array(
        'User'=>array(
            'uname','email','point','regip','unread','isdel'=>'user_isdel',
            '_on'=>'UserAddress.uid=User.uid',
            '_fields' => array('FROM_UNIXTIME(User.lasttime) AS user_lasttime',
                'FROM_UNIXTIME(User.regtime) AS user_regtime'),
        ),
    );

    //关联表
    public $_relation = array('User');
   


} //End Of Class
