<?php

if( !class_exists('CommonView') ) {
    require 'CommonView.class.php';
}

/** 
 * OrderModel extends CommonView 订单视图模型
 * @date   2013-04-17 
 */
class OrderView extends CommonView {
   
    //主表
    public $viewFields = array(
        'Order'=>array('orderid','tradeid','uid','uname','itemname'=>'order_itemname',
                    'itemid','num','isdel'=>'order_isdel',
            '_as'=>'Orders',
            '_fields' => array('FORMAT(Orders.price/100,2) AS order_price'),
        ),
    );  

    //发货单
    protected $Trade = array(
        'Trade'=>array('status'=>'trade_status','receiver_name',
                       'receiver_address','receiver_link',
                       'payment','posttime','deli_status','deli_man','is_mobile_discount',
            '_on'=>'Orders.tradeid=Trade.tradeid',
            '_fields' => array('FORMAT(Trade.totalfee/100,2) AS totalfee',
                        'FROM_UNIXTIME(Trade.paytime) AS paytime',
                        'FROM_UNIXTIME(Trade.sendtime) AS sendtime',
                        ),
        ),
    );

    
    //商品
    protected $Item = array(
        'Item'=>array('itemname','inventory','typeid','point','view',
                'volume','status'=>'item_status','isdel'=>'item_isdel',
            '_on'=>'Orders.itemid=Item.itemid',
            '_fields' => array('FORMAT(Item.price/100,2) AS price'),
        ),
    );
    

    //规格
    protected $Spec = array(
        'ProductSpec'=>array('_on'=>'Orders.productid=ProductSpec.productid'),
        'Specval'=>array('name'=>'specval_name','_on'=>'Specval.specvalid=ProductSpec.specvalid'),
    );


    //关联表
    public $_relation = array('Trade','Item','Spec');

   

} //End Of Class
