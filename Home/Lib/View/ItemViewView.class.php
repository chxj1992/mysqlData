<?php

if( !class_exists('CommonView') ) {
    require 'CommonView.class.php';
}


class ItemViewView extends CommonView {
   
    //主表
    public $viewFields = array(

        'ItemView'=>array('item_name'=>'itemname','time'=>'view_time','view','item_id'=>'itemid'),

    );  

   

} //End Of Class
