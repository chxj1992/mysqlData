<?php

if( !class_exists('CommonView') ) {
    require 'CommonView.class.php';
}

/** 
 * ItemView extends CommonView 商品视图模型
 * @date   2013-04-17 
 */
class ItemView extends CommonView {
    
    //主表
    public $viewFields = array(
        'Item'=>array('itemid','itemname',
                'inventory','typeid','point','view','volume',
                'status'=>'item_status','isdel'=>'item_isdel',
            '_fields' => array('FORMAT(Item.price/100,2) AS `price`'),
            ),
    );  

    //类目
    protected $Type = array(
        'Type'=>array('typename','_on'=>'Type.typeid=Item.typeid'),
    );
    //分类
    protected $Cat = array(
        'ItemCat'=>array('catid','_on'=>'Item.itemid=ItemCat.itemid'),
        'Cat'=>array('catname','_on'=>'Cat.catid=ItemCat.catid'),
    );

    //关联表
    public $_relation = array('Type','Cat');
    


} //End Of Class
