<?php

if( !class_exists('CommonView') ) {
    require 'CommonView.class.php';
}

/** 
 * TradeView extends CommonView 发货单视图模型
 * @date   2013-04-17 
 */
class TradeView extends CommonView {

    //主表
    public $viewFields = array(
        'Trade'=>array( 
                    'tradeid','status'=>'trade_status','receiver_name','receiver_address','uid',
                    'receiver_link','payment','posttime','deli_status','deli_man',
                '_fields' => array('FROM_UNIXTIME(Trade.sendtime) AS sendtime',
                                'FROM_UNIXTIME(Trade.paytime) AS paytime',
                                'FORMAT(Trade.totalfee/100,2) AS totalfee'),
        ),
    );  

    //送货员
    protected $DeliMan = array(
        'Admin'=>array(
                'adminid'=>'deli_id','uname'=>'deli_uname','name'=>'deli_name','isdel'=>'deli_isdel',                               
            '_on'=>'Trade.deli_man=Admin.adminid'),
    );
 
    //用户
    protected $User = array(
        'User'=>array(
            'uname','email','point','regip'=>'regip','unread','isdel'=>'user_isdel',
             '_fields' => array('FROM_UNIXTIME(User.lasttime) AS user_lasttime',
                'FROM_UNIXTIME(User.regtime) AS user_regtime'),
            '_on'=>'Trade.uid=User.uid'),
    );
  
   
    //关联表
    public $_relation = array('DeliMan','User');
    

} //End Of Class
